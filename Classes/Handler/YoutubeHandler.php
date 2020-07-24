<?php
namespace IchHabRecht\SocialGdpr\Handler;

use IchHabRecht\SocialGdpr\Service\YoutubeImageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

class YoutubeHandler implements HandlerInterface
{
    /**
     * @var array
     */
    protected $matches = [];

    /**
     * @var YoutubeImageService
     */
    protected $youtubeImageService;

    public function __construct(YoutubeImageService $youtubeImageService = null)
    {
        $this->youtubeImageService = $youtubeImageService ?: GeneralUtility::makeInstance(YoutubeImageService::class);
    }

    /**
     * @param string $content
     * @return bool
     */
    public function hasMatches($content)
    {
        preg_match_all(
            '/<iframe(?:(?:src="(?:(?:https?:)?\/\/)?(?:www\.)?youtube(?:-nocookie)?.*?\/(?:embed\/|watch\?v=|)(?<id>[a-z_A-Z0-9\-]{11})[^"]*?"|height="(?<height>[^"]+)"|width="(?<width>[^"]+)"|(?!src)[^>])+)>.*?<\/iframe>/i',
            $content,
            $this->matches,
            PREG_SET_ORDER
        );

        return !empty($this->matches);
    }

    /**
     * @return Match[]
     */
    public function getMatches()
    {
        return array_map(
            function ($match) {
                return new Match(
                    $match[0],
                    [
                        'uid' => StringUtility::getUniqueId(),
                        'id' => $match['id'],
                        'iframeHash' => base64_encode($match[0]),
                        'height' => !empty($match['height']) ? (int)$match['height'] : 0,
                        'width' => !empty($match['width']) ? (int)$match['width'] : 0,
                        'preview' => $this->youtubeImageService->getPreviewImage($match['id']),
                    ]
                );
            },
            $this->matches
        );
    }
}
