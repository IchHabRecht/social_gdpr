<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Hooks;

use IchHabRecht\SocialGdpr\Handler\ContentMatch;
use IchHabRecht\SocialGdpr\Handler\HandlerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class ContentPostProcessHook
{
    /**
     * @var ContentObjectRenderer|null
     */
    protected $contentObjectRenderer;

    public function __construct(ContentObjectRenderer $contentObjectRenderer = null)
    {
        $this->contentObjectRenderer = $contentObjectRenderer ?: GeneralUtility::makeInstance(ContentObjectRenderer::class);
    }

    public function replaceSocialMedia(array $parameter)
    {
        $typoScriptFrontendController = $parameter['pObj'];

        $content = $typoScriptFrontendController->content;

        foreach ((array)$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['social_gdpr']['handler'] as $templateName => $className) {
            $handler = GeneralUtility::makeInstance($className);
            if (!$handler instanceof HandlerInterface) {
                throw new \RuntimeException(
                    'Handler "' . $templateName . '" doesn\'t implement IchHabRecht\\SocialGdpr\\Handler\\HandlerInterface',
                    1587740236
                );
            }

            if ($handler->hasMatches($content)) {
                $matches = $handler->getMatches();
                foreach ($matches as $match) {
                    if (!$match instanceof ContentMatch) {
                        throw new \RuntimeException(
                            'Match needs to be an instance of \\IchHabRecht\\SocialGdpr\\Handler\\Match',
                            1587741462
                        );
                    }

                    $data = array_merge(
                        $match->getData(),
                        [
                            'templateName' => $templateName,
                        ]
                    );

                    $this->contentObjectRenderer->start($data, 'tt_content');
                    $handlerContent = $this->contentObjectRenderer->cObjGetSingle($typoScriptFrontendController->tmpl->setup['lib.']['socialgdpr'], $typoScriptFrontendController->tmpl->setup['lib.']['socialgdpr.']);
                    $content = str_replace($match->getSearch(), $handlerContent, $content);
                }
            }
        }

        $typoScriptFrontendController->content = $content;
    }
}
