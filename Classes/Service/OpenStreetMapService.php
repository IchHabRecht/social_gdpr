<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Service;

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\RequestException;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

class OpenStreetMapService
{
    /**
     * @var string
     */
    protected $apiUri = 'https://render.openstreetmap.org/cgi-bin/export?bbox=###BBOX###&scale=1550&format=png';

    /**
     * @var RequestFactory
     */
    protected $requestFactory;

    public function __construct(RequestFactory $requestFactory = null)
    {
        $this->requestFactory = $requestFactory ?: GeneralUtility::makeInstance(RequestFactory::class);
    }

    public function getPreviewImage($bbox): string
    {
        $filename = GeneralUtility::getFileAbsFileName('typo3temp/assets/tx_socialgdpr/osm_' . md5($bbox) . '.png');
        $fileExists = file_exists($filename);

        if (!$fileExists) {
            try {
                $cookies = new CookieJar();
                $uri = str_replace('###BBOX###', $bbox, $this->apiUri);
                $this->requestFactory->request('https://www.openstreetmap.org/', 'GET', ['cookies' => $cookies]);
                $response = $this->requestFactory->request($uri, 'GET', ['cookies' => $cookies, 'sink' => $filename]);
                if ($response->getStatusCode() === 200) {
                    // GeneralUtility::writeFileToTypo3tempDir($filename, $response->getBody()->getContents());
                    $fileExists = true;
                }
            } catch (RequestException $e) {
            }
        }

        return $fileExists ? PathUtility::getAbsoluteWebPath($filename) : '';
    }
}
