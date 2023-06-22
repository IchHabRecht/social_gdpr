<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Form\CustomInlineControl;

use TYPO3\CMS\Backend\Form\Element\InlineElementHookInterface;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class YoutubePreviewFlush implements InlineElementHookInterface
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
        if (($childRecord['uid_local'][0]['row']['extension'] ?? '') !== 'youtube') {
            return;
        }
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);

        $pageRenderer->loadRequireJsModule('TYPO3/CMS/SocialGdpr/Backend/YoutubePreviewFlush');
        $fileUid = (int)$childRecord['uid_local'][0]['row']['uid'];
        $file = $resourceFactory->getFileObject($fileUid);
        $youtubeHelper = GeneralUtility::makeInstance(OnlineMediaHelperRegistry::class)->getOnlineMediaHelper($file);
        $youtubeId = $youtubeHelper->getOnlineMediaId($file);
        $attributes = [
            'type' => 'button',
            'class' => 'btn btn-default',
            'data-youtube-id' => $youtubeId,
            'title' => 'Flush YouTube Thumbnail for id ' . $youtubeId,
        ];
        $icon = $iconFactory->getIcon('mimetypes-media-video-youtube', Icon::SIZE_SMALL)->render();
        $controlItems['youtubeFlush'] = '<button' . GeneralUtility::implodeAttributes($attributes, true) . '> ' . $icon . ' </button>';
    }
}
