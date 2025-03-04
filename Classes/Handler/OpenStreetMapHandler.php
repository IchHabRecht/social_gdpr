<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Handler;

use IchHabRecht\SocialGdpr\Service\OpenStreetMapService;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

class OpenStreetMapHandler implements HandlerInterface
{
    /**
     * @var array
     */
    protected $matches = [];

    public function __construct(protected OpenStreetMapService $openStreetMapService)
    {
    }

    /**
     * @param string $content
     * @return bool
     */
    public function hasMatches(string $content): bool
    {
        preg_match_all(
            '/<iframe(?:(?: src="(?:(?:https?:)?\/\/)?(?:(?:www|umap)\.)?openstreetmap\.(?:org|de|fr)\/(?:export\/embed.html\?bbox=(?<bbox>[^&]+)|[a-z]{2}\/map\/)[^"]*?"| height="(?<height>[^"]+)"| width="(?<width>[^"]+)"|(?!src)[^>])+)>.*?<\/iframe>/i',
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
            function ($match) {
                return new ContentMatch(
                    $match[0],
                    [
                        'uid' => StringUtility::getUniqueId(),
                        'iframeHash' => base64_encode($match[0]),
                        'height' => !empty($match['height']) ? (MathUtility::canBeInterpretedAsInteger($match['height']) ? $match['height'] . 'px' : $match['height']) : 0,
                        'width' => !empty($match['width']) ? (MathUtility::canBeInterpretedAsInteger($match['width']) ? $match['width'] . 'px' : $match['width']) : 0,
                        'preview' => $this->openStreetMapService->getPreviewImage($match['bbox']),
                    ]
                );
            },
            $this->matches
        );
    }
}
