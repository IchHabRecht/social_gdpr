<?php
namespace IchHabRecht\SocialGdpr\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

class VimeoImageService extends AbstractImageService
{
    /**
     * @var string
     */
    protected $apiUri = 'https://vimeo.com/api/v2/video/###ID###.json';

    /**
     * @param string $id
     * @return string
     */
    public function getPreviewImage($id)
    {
        $filename = GeneralUtility::getFileAbsFileName('typo3temp/assets/tx_socialgdpr/vimeo_' . md5($id) . '.jpg');
        $fileExists = file_exists($filename);

        if (!$fileExists) {
            $uri = str_replace('###ID###', $id, $this->apiUri);
            $statusCode = null;
            $content = $this->getRequest($uri, $statusCode);
            if ($statusCode === 200) {
                $json = json_decode($content, true);
                if (!empty($json[0]['thumbnail_large']) || !empty($json[0]['thumbnail_medium']) || !empty($json[0]['thumbnail_small'])) {
                    $thumbnailUri = $json[0]['thumbnail_large'] ?: $json[0]['thumbnail_medium'] ?: $json[0]['thumbnail_small'];
                    $thumbnailStatusCode = null;
                    $thumbnailContent = $this->getRequest($thumbnailUri, $thumbnailStatusCode);
                    if ($thumbnailStatusCode === 200) {
                        GeneralUtility::writeFileToTypo3tempDir($filename, $thumbnailContent);
                        $fileExists = true;
                    }
                }
            }
        }

        return $fileExists ? PathUtility::getAbsoluteWebPath($filename) : '';
    }
}
