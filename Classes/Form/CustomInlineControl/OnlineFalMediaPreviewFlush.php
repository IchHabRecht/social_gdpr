<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Form\CustomInlineControl;

use IchHabRecht\SocialGdpr\Service\PreviewImageServiceRegistry;
use TYPO3\CMS\Backend\Form\Element\InlineElementHookInterface;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class OnlineFalMediaPreviewFlush implements InlineElementHookInterface
{
    public function renderForeignRecordHeaderControl_preProcess(
        $parentUid,
        $foreignTable,
        array $childRecord,
        array $childConfig,
        $isVirtual,
        array &$enabledControls
    ) {
        // Do nothing.
    }

    public function renderForeignRecordHeaderControl_postProcess(
        $parentUid,
        $foreignTable,
        array $childRecord,
        array $childConfig,
        $isVirtual,
        array &$controlItems
    ) {
        if ($foreignTable !== 'sys_file_reference') {
            return;
        }
        $fileExtension = $childRecord['uid_local'][0]['row']['extension'] ?? '';
        $previewImageServiceRegistry = GeneralUtility::makeInstance(PreviewImageServiceRegistry::class);
        $onlineMediaRegistry = GeneralUtility::makeInstance(OnlineMediaHelperRegistry::class);
        if (
            !$previewImageServiceRegistry->hasPreviewImageService($fileExtension)
            || !$onlineMediaRegistry->hasOnlineMediaHelper($fileExtension)
        ) {
            return;
        }

        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);

        $pageRenderer->loadRequireJsModule('TYPO3/CMS/SocialGdpr/Backend/PreviewImageFlush');
        $fileUid = (int)$childRecord['uid_local'][0]['row']['uid'];
        $file = $resourceFactory->getFileObject($fileUid);
        $onlineMediaHelper = $onlineMediaRegistry->getOnlineMediaHelper($file);
        $id = $onlineMediaHelper->getOnlineMediaId($file);
        $attributes = [
            'type' => 'button',
            'class' => 'btn btn-default',
            'data-preview-image-id' => $id,
            'data-preview-image-type' => $fileExtension,
            'title' => 'Flush preview image',
        ];
        $icon = $iconFactory->getIcon('actions-delete', Icon::SIZE_SMALL, $iconRegistry->getIconIdentifierForFileExtension($fileExtension))->render();
        $controlItems['youtubeFlush'] = '<button' . GeneralUtility::implodeAttributes($attributes, true) . '> ' . $icon . ' </button>';
    }
}
