<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Form\CustomInlineControl;

use IchHabRecht\SocialGdpr\Service\PreviewImageServiceRegistry;
use TYPO3\CMS\Backend\Form\Event\ModifyFileReferenceControlsEvent;
use TYPO3\CMS\Backend\Form\Event\ModifyInlineElementControlsEvent;
use TYPO3\CMS\Backend\Form\Event\ModifyInlineElementEnabledControlsEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;


#[AsEventListener(
    identifier: 'socialgdprModifyFileReferenceControlsEvent',
    event: ModifyFileReferenceControlsEvent::class,
    method: 'renderFileReferenceHeaderControl',
)]
#[AsEventListener(
    identifier: 'socialgdprModifyInlineElementControlsEvent',
    event: ModifyInlineElementControlsEvent::class,
    method: 'modifyControls',
)]
class OnlineFalMediaPreviewFlushEventListener
{
    protected IconFactory $iconFactory;

    protected IconRegistry $iconRegistry;

    protected OnlineMediaHelperRegistry $onlineMediaRegistry;

    protected PageRenderer $pageRenderer;

    protected PreviewImageServiceRegistry $previewImageServiceRegistry;

    protected ResourceFactory $resourceFactory;

    public function __construct(
        ?IconFactory $iconFactory = null,
        ?IconRegistry $iconRegistry = null,
        ?OnlineMediaHelperRegistry $onlineMediaRegistry = null,
        ?PageRenderer $pageRenderer = null,
        ?PreviewImageServiceRegistry $previewImageServiceRegistry = null,
        ?ResourceFactory $resourceFactory = null
    ) {
        $this->iconFactory = $iconFactory ?: GeneralUtility::makeInstance(IconFactory::class);
        $this->iconRegistry = $iconRegistry ?: GeneralUtility::makeInstance(IconRegistry::class);
        $this->onlineMediaRegistry = $onlineMediaRegistry ?: GeneralUtility::makeInstance(OnlineMediaHelperRegistry::class);
        $this->pageRenderer = $pageRenderer ?: GeneralUtility::makeInstance(PageRenderer::class);
        $this->previewImageServiceRegistry = $previewImageServiceRegistry ?: GeneralUtility::makeInstance(PreviewImageServiceRegistry::class);
        $this->resourceFactory = $resourceFactory ?: GeneralUtility::makeInstance(ResourceFactory::class);
    }

    public function renderFileReferenceHeaderControl(ModifyFileReferenceControlsEvent $event): void
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

        $this->pageRenderer->loadJavaScriptModule('@vendor/social_gdpr/Backend/PreviewImageFlush.js');
        $controls = $event->getControls();
        $controls['youtubeFlush'] = $this->renderControlItem($record);
        $event->setControls($controls);
    }

    public function modifyControls(ModifyInlineElementControlsEvent $event): void
    {
        if ($event->getElementData()['inlineParentTableName'] !== 'sys_file_reference') {
            return;
        }

        $fileExtension = $childRecord['uid_local'][0]['row']['extension'] ?? '';
        if (
            !$this->previewImageServiceRegistry->hasPreviewImageService($fileExtension)
            || !$this->onlineMediaRegistry->hasOnlineMediaHelper($fileExtension)
        ) {
            return;
        }

        $this->pageRenderer->loadJavaScriptModule('@vendor/social_gdpr/Backend/PreviewImageFlush.js');
        $controlItems['youtubeFlush'] = $this->renderControlItem($childRecord);
        $event->setControls($controlItems);
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
