<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Hooks;

use IchHabRecht\SocialGdpr\Handler\ContentMatch;
use IchHabRecht\SocialGdpr\Handler\HandlerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

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

    public function replaceSocialMediaWithEvent(\TYPO3\CMS\Frontend\Event\AfterCacheableContentIsGeneratedEvent $event)
    {
        $this->replaceSocialMediaInContent($event->getController());
    }

    protected function replaceSocialMediaInContent(TypoScriptFrontendController $typoScriptFrontendController)
    {
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
                    $typoScript = $this->contentObjectRenderer->getRequest()->getAttribute('frontend.typoscript')->getSetupArray();
                    $handlerContent = $this->contentObjectRenderer->cObjGetSingle($typoScript['lib.']['socialgdpr'], $typoScript['lib.']['socialgdpr.']);
                    $content = str_replace($match->getSearch(), $handlerContent, $content);
                }
            }
        }

        $typoScriptFrontendController->content = $content;
    }
}
