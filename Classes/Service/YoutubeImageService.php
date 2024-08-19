<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Service;

use GuzzleHttp\Exception\RequestException;
use IchHabRecht\SocialGdpr\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

class YoutubeImageService implements PreviewImageServiceInterface
{
    /**
     * @var string
     */
    protected $baseUri = 'https://img.youtube.com/vi';

    /**
     * @var string[]
     */
    protected $possiblePreviewNames = [
        'maxresdefault.jpg',
        'sddefault.jpg',
        'hqdefault.jpg',
        'mqdefault.jpg',
        '0.jpg',
    ];

    /**
     * @var ExtensionConfiguration
     */
    protected $extensionConfiguration;

    /**
     * @var RequestFactory
     */
    protected $requestFactory;

    public function __construct(ExtensionConfiguration $extensionConfiguration = null, RequestFactory $requestFactory = null)
    {
        $this->extensionConfiguration = $extensionConfiguration ?: GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $this->requestFactory = $requestFactory ?: GeneralUtility::makeInstance(RequestFactory::class);
    }

    public function getPreviewImage(string $id): string
    {
        if (!$this->extensionConfiguration->isEnabled('youtubePreview')) {
            return '';
        }

        $filename = GeneralUtility::getFileAbsFileName('typo3temp/assets/tx_socialgdpr/youtube_' . md5($id) . '.jpg');
        $fileExists = file_exists($filename);

        if (!$fileExists) {
            foreach ($this->possiblePreviewNames as $previewName) {
                try {
                    $uri = implode('/', [$this->baseUri, $id, $previewName]);
                    $response = $this->requestFactory->request($uri);
                    if ($response->getStatusCode() === 200) {
                        GeneralUtility::writeFileToTypo3tempDir($filename, $response->getBody()->getContents());
                        $fileExists = true;
                        break;
                    }
                } catch (RequestException $e) {
                }
            }
        }

        return $fileExists ? PathUtility::getAbsoluteWebPath($filename) : '';
    }

    public function deletePreviewImage(string $id): void
    {
        $absolutePath = $this->getAbsoluteFileName($id);
        unlink($absolutePath);
    }

    public function hasPreviewImage(string $id): bool
    {
        $absolutePath = $this->getAbsoluteFileName($id);

        return file_exists($absolutePath);
    }

    protected function getAbsoluteFileName(string $id): string
    {
        $fileName = 'youtube_' . md5($id) . '.jpg';
        $absoluteFileName = GeneralUtility::getFileAbsFileName('typo3temp/assets/tx_socialgdpr/' . $fileName);

        return $absoluteFileName;
    }
}
