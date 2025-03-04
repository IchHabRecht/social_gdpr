<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Form\CustomInlineControl;

use IchHabRecht\SocialGdpr\Service\PreviewImageServiceRegistry;
use TYPO3\CMS\Backend\Form\Event\ModifyFileReferenceControlsEvent;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class OnlineFalMediaPreviewFlush
{
    public function __construct(
        protected IconFactory $iconFactory,
        protected IconRegistry $iconRegistry,
        protected OnlineMediaHelperRegistry $onlineMediaRegistry,
        protected PageRenderer $pageRenderer,
        protected PreviewImageServiceRegistry $previewImageServiceRegistry,
        protected ResourceFactory $resourceFactory
    ) {
    }

    public function renderFileReferenceHeaderControl(ModifyFileReferenceControlsEvent $event)
    {
        $elementData = $event->getElementData();
        if ($elementData['tableName'] !== 'sys_file_reference') {
            return;
        }

        $record = $event->getRecord();
        $fileExtension = $record['uid_local'][0]['row']['extension'] ?? '';
        if (
            !$this->previewImageServiceRegistry->hasPreviewImageService($fileExtension)
            || !$this->onlineMediaRegistry->hasOnlineMediaHelper($fileExtension)
        ) {
            return;
        }

        $this->pageRenderer->loadJavaScriptModule('@ichhabrecht/social-gdpr/backend/preview_image_flush.js');
        $controls = $event->getControls();
        $controls['youtubeFlush'] = $this->renderControlItem($record);
        $event->setControls($controls);
    }

    protected function renderControlItem(array $record): string
    {
        $fileExtension = $record['uid_local'][0]['row']['extension'];
        $fileUid = (int)$record['uid_local'][0]['row']['uid'];
        $file = $this->resourceFactory->getFileObject($fileUid);
        $onlineMediaHelper = $this->onlineMediaRegistry->getOnlineMediaHelper($file);
        $id = $onlineMediaHelper->getOnlineMediaId($file);
        $attributes = [
            'type' => 'button',
            'class' => 'btn btn-default',
            'data-preview-image-id' => $id,
            'data-preview-image-type' => $fileExtension,
            'title' => 'Flush preview image',
        ];
        $icon = $this->iconFactory->getIcon('actions-delete', Icon::SIZE_SMALL, $this->iconRegistry->getIconIdentifierForFileExtension($fileExtension))->render();

        return '<button' . GeneralUtility::implodeAttributes($attributes, true) . '> ' . $icon . ' </button>';
    }
}
