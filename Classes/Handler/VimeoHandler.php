<?php
namespace IchHabRecht\SocialGdpr\Handler;

use IchHabRecht\SocialGdpr\Service\VimeoImageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

class VimeoHandler implements HandlerInterface
{
    /**
     * @var array
     */
    protected $matches = [];

    /**
     * @var VimeoImageService
     */
    protected $vimeoImageService;

    public function __construct(VimeoImageService $vimeoImageService = null)
    {
        $this->vimeoImageService = $vimeoImageService ?: GeneralUtility::makeInstance(VimeoImageService::class);
    }

    /**
     * @param string $content
     * @return bool
     */
    public function hasMatches($content)
    {
        preg_match_all(
            '/<iframe(?:(?:src="(?:(?:https?:)?\/\/)?(?:www\.)?(?:player\.)?vimeo\.com\/video\/(?<id>[a-z_A-Z0-9\-]+)[^"]*?"|height="(?<height>[^"]+)"|width="(?<width>[^"]+)"|(?!src)[^>])+)>.*?<\/iframe>/i',
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
                        'preview' => $this->vimeoImageService->getPreviewImage($match['id']),
                    ]
                );
            },
            $this->matches
        );
    }
}
