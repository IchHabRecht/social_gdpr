<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Service;

use GuzzleHttp\Exception\RequestException;
use IchHabRecht\SocialGdpr\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

class VimeoImageService implements PreviewImageServiceInterface
{
    /**
     * @var string
     */
    protected $apiUri = 'https://vimeo.com/api/v2/video/###ID###.json';

    public function __construct(
        protected ExtensionConfiguration $extensionConfiguration,
        protected RequestFactory $requestFactory
    ) {
    }

    public function getPreviewImage(string $id): string
    {
        if (!$this->extensionConfiguration->isEnabled('vimeoPreview')) {
            return '';
        }

        $filename = GeneralUtility::getFileAbsFileName('typo3temp/assets/tx_socialgdpr/vimeo_' . md5($id) . '.jpg');
        $fileExists = file_exists($filename);

        if (!$fileExists) {
            try {
                $uri = str_replace('###ID###', $id, $this->apiUri);
                $response = $this->requestFactory->request($uri);
                if ($response->getStatusCode() === 200) {
                    $json = json_decode($response->getBody()->getContents(), true);
                    if (!empty($json[0]['thumbnail_large']) || !empty($json[0]['thumbnail_medium']) || !empty($json[0]['thumbnail_small'])) {
                        $thumbnailUri = $json[0]['thumbnail_large'] ?: $json[0]['thumbnail_medium'] ?: $json[0]['thumbnail_small'];
                        $thumbnailResponse = $this->requestFactory->request($thumbnailUri);
                        if ($thumbnailResponse->getStatusCode() === 200) {
                            GeneralUtility::writeFileToTypo3tempDir($filename, $thumbnailResponse->getBody()->getContents());
                            $fileExists = true;
                        }
                    }
                }
            } catch (RequestException $e) {
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
        $fileName = 'vimeo_' . md5($id) . '.jpg';
        $absoluteFileName = GeneralUtility::getFileAbsFileName('typo3temp/assets/tx_socialgdpr/' . $fileName);

        return $absoluteFileName;
    }
}
