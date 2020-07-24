<?php
namespace IchHabRecht\SocialGdpr\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

class YoutubeImageService extends AbstractImageService
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
     * @param string $id
     * @return string
     */
    public function getPreviewImage($id)
    {
        $filename = GeneralUtility::getFileAbsFileName('typo3temp/assets/tx_socialgdpr/youtube_' . md5($id) . '.jpg');
        $fileExists = file_exists($filename);

        if (!$fileExists) {
            foreach ($this->possiblePreviewNames as $previewName) {
                $uri = implode('/', [$this->baseUri, $id, $previewName]);
                $statusCode = null;
                $content = $this->getRequest($uri, $statusCode);
                if ($statusCode === 200) {
                    GeneralUtility::writeFileToTypo3tempDir($filename, $content);
                    $fileExists = true;
                    break;
                }
            }
        }

        return $fileExists ? PathUtility::getAbsoluteWebPath($filename) : '';
    }
}
