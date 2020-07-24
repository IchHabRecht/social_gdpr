<?php
namespace IchHabRecht\SocialGdpr\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractImageService
{
    /**
     * @param string $uri
     * @param int $statusCode
     * @return string
     */
    protected function getRequest($uri, &$statusCode)
    {
        $response = null;
        $content = GeneralUtility::getUrl($uri, 0, null, $response);

        $statusCode = 0;
        if (in_array($response['lib'], ['cURL', 'GuzzleHttp'], true)) {
            $statusCode = !empty($response['http_code']) ? (int)$response['http_code'] : (int)$response['error'];
        } elseif (in_array($response['lib'], ['file', 'socket'], true) && $response['error'] === 0) {
            $statusCode = 200;
        }

        return $content;
    }
}
