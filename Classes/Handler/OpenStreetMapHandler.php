<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Handler;

use IchHabRecht\SocialGdpr\Service\OpenStreetMapService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

class OpenStreetMapHandler implements HandlerInterface
{
    /**
     * @var array
     */
    protected $matches = [];

    /**
     * @var OpenStreetMapService
     */
    protected $openStreetMapService;

    public function __construct(OpenStreetMapService $openStreetMapService = null)
    {
        $this->openStreetMapService = $openStreetMapService ?: GeneralUtility::makeInstance(OpenStreetMapService::class);
    }

    /**
     * @param string $content
     * @return bool
     */
    public function hasMatches(string $content): bool
    {
        preg_match_all(
            '/<iframe(?:(?: src="(?:(?:https?:)?\/\/)?(?:www\.)?openstreetmap\.org\/export\/embed.html\?bbox=(?<bbox>[^&]+)[^"]*?"| height="(?<height>[^"]+)"| width="(?<width>[^"]+)"|(?!src)[^>])+)>.*?<\/iframe>/i',
            $content,
            $this->matches,
            PREG_SET_ORDER
        );

        return !empty($this->matches);
    }

    /**
     * @return ContentMatch[]
     */
    public function getMatches(): array
    {
        return array_map(
            fn ($match) => new ContentMatch(
                $match[0],
                [
                    'uid' => StringUtility::getUniqueId(),
                    'iframeHash' => base64_encode((string) $match[0]),
                    'height' => !empty($match['height']) ? (MathUtility::canBeInterpretedAsInteger($match['height']) ? $match['height'] . 'px' : $match['height']) : 0,
                    'width' => !empty($match['width']) ? (MathUtility::canBeInterpretedAsInteger($match['width']) ? $match['width'] . 'px' : $match['width']) : 0,
                    'preview' => $this->openStreetMapService->getPreviewImage($match['bbox']),
                ]
            ),
            $this->matches
        );
    }
}
