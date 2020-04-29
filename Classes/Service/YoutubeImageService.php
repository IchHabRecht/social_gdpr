<?php
declare(strict_types = 1);
namespace IchHabRecht\SocialGdpr\Service;

use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

class YoutubeImageService
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
        'mqdefault.jpg',
        'hqdefault.jpg',
        '0.jpg',
    ];

    /**
     * @var RequestFactory
     */
    protected $requestFactory;

    public function __construct(RequestFactory $requestFactory = null)
    {
        $this->requestFactory = $requestFactory ?: GeneralUtility::makeInstance(RequestFactory::class);
    }

    public function getPreviewImage($id): string
    {
        $filename = GeneralUtility::getFileAbsFileName('typo3temp/assets/tx_socialgdpr/youtube_' . md5($id) . '.jpg');
        $fileExists = file_exists($filename);

        if (!$fileExists) {
            foreach ($this->possiblePreviewNames as $previewName) {
                $uri = implode('/', [$this->baseUri, $id, $previewName]);
                $response = $this->requestFactory->request($uri);
                if ($response->getStatusCode() === 200) {
                    GeneralUtility::writeFileToTypo3tempDir($filename, $response->getBody()->getContents());
                    $fileExists = true;
                    break;
                }
            }
        }

        return $fileExists ? PathUtility::getAbsoluteWebPath($filename) : '';
    }
}
