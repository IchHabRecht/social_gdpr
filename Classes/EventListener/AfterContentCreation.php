<?php

namespace IchHabRecht\SocialGdpr\EventListener;

use IchHabRecht\SocialGdpr\Handler\ContentMatch;
use IchHabRecht\SocialGdpr\Handler\HandlerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Event\AfterCacheableContentIsGeneratedEvent;

class AfterContentCreation
{
    public function __invoke(AfterCacheableContentIsGeneratedEvent $event): void
    {
        $content = $event->getController()->content;
        $typoScriptFrontendController = $event->getController();
        foreach ((array)$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['social_gdpr']['handler'] as $templateName => $className) {
            $handler = GeneralUtility::makeInstance($className);
            if (!$handler instanceof HandlerInterface) {
                throw new \RuntimeException(
                    sprintf('Handler "%s" does not implement HandlerInterface', $templateName),
                    1587740236
                );
            }

            if ($handler->hasMatches($content)) {
                foreach ($handler->getMatches() as $match) {
                    if (!$match instanceof ContentMatch) {
                        throw new \RuntimeException(
                            'Match needs to be an instance of ContentMatch',
                            1587741462
                        );
                    }

                    $data = array_merge($match->getData(), ['templateName' => $templateName]);
                    $cObjRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
                    $cObjRenderer->start($data, 'tt_content');

                    $handlerContent = $cObjRenderer->cObjGetSingle(
                        $typoScriptFrontendController->tmpl->setup['lib.']['socialgdpr'],
                        $typoScriptFrontendController->tmpl->setup['lib.']['socialgdpr.']
                    );

                    $content = str_replace($match->getSearch(), $handlerContent, $content);
                }
            }
        }

        $event->getController()->content = $content;
    }
}
