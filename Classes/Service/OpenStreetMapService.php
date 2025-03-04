<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Service;

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\RequestException;
use IchHabRecht\SocialGdpr\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

class OpenStreetMapService
{
    /**
     * @var string
     */
    protected $apiUri = 'https://render.openstreetmap.org/cgi-bin/export?bbox=###BBOX###&scale=###scale###&format=png';

    public function __construct(
        protected ExtensionConfiguration $extensionConfiguration,
        protected RequestFactory $requestFactory
    ) {
    }

    public function getPreviewImage($bbox): string
    {
        if (!$this->extensionConfiguration->isEnabled('osmPreview')) {
            return '';
        }

        $filename = GeneralUtility::getFileAbsFileName('typo3temp/assets/tx_socialgdpr/osm_' . md5($bbox) . '.png');
        $fileExists = file_exists($filename);

        if (!$fileExists) {
            try {
                $cookies = new CookieJar();
                $this->requestFactory->request('https://www.openstreetmap.org/', 'GET', ['cookies' => $cookies]);
                for ($i = 1; $i < 4; $i++) {
                    try {
                        $uri = str_replace(['###BBOX###', '###scale###'], [$bbox, $i * 2850], $this->apiUri);
                        $response = $this->requestFactory->request($uri, 'GET', ['cookies' => $cookies]);
                        if ($response->getStatusCode() === 200) {
                            GeneralUtility::writeFileToTypo3tempDir($filename, $response->getBody()->getContents());
                            $fileExists = true;
                            break;
                        }
                    } catch (RequestException $e) {
                    }
                }
            } catch (RequestException $e) {
            }
        }

        return $fileExists ? PathUtility::getAbsoluteWebPath($filename) : '';
    }
}
