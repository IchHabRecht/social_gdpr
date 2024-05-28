<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class PreviewImageServiceRegistry
{
    /**
     * @var array<string, PreviewImageServiceInterface>
     */
    protected array $previewImageServices = [
        'youtube' => YoutubeImageService::class,
        'vimeo' => VimeoImageService::class,
    ];

    public function hasPreviewImageService(string $extension): bool
    {
        return array_key_exists($extension, $this->previewImageServices);
    }

    public function getPreviewImageService(string $extension): PreviewImageServiceInterface
    {
        return GeneralUtility::makeInstance($this->previewImageServices[$extension]);
    }
}
