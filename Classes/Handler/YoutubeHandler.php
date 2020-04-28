<?php
declare(strict_types = 1);
namespace IchHabRecht\SocialGdpr\Handler;

use TYPO3\CMS\Core\Utility\StringUtility;

class YoutubeHandler implements HandlerInterface
{
    /**
     * @var array
     */
    protected $matches = [];

    public function hasMatches(string $content): bool
    {
        preg_match_all(
            '/<iframe(?:(?:src="(?:https?:\/\/)?(?:www\.)?yout(?:.*?)\/(?:embed\/|watch.*?v=|)(?<id>[a-z_A-Z0-9\-]{11})[^"]*?"|height="(?<height>[^"]+)"|width="(?<width>[^"]+)"|[^>])+)>.*?<\/iframe>/i',
            $content,
            $this->matches,
            PREG_SET_ORDER
        );

        return !empty($this->matches);
    }

    public function getMatches(): array
    {
        return array_map(
            function ($match) {
                return new Match(
                    $match[0],
                    [
                        'uid' => StringUtility::getUniqueId(),
                        'id' => $match['id'],
                        'iframeHash' => base64_encode($match[0]),
                        'height' => (int)($match['height'] ?? 0),
                        'width' => (int)($match['width'] ?? 0),
                    ]
                );
            },
            $this->matches
        );
    }
}
