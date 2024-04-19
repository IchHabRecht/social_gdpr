<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Handler;

use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

class GoogleMapsIframeHandler implements HandlerInterface
{
    /**
     * @var array
     */
    protected $matches = [];

    /**
     * @param string $content
     * @return bool
     */
    public function hasMatches(string $content): bool
    {
        preg_match_all(
            '/<iframe(?:(?:src="(?:(?:https?:)?\/\/)?(?:www\.)?google\.[a-z]+\/maps\/[^"]*?"| height="(?<height>[^"]+)"| width="(?<width>[^"]+)"|(?!src)[^>])+)>.*?<\/iframe>/i',
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
                ]
            ),
            $this->matches
        );
    }
}
