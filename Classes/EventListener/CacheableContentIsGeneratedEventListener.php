<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\EventListener;

use IchHabRecht\SocialGdpr\Handler\ContentMatch;
use IchHabRecht\SocialGdpr\Handler\HandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Event\AfterCacheableContentIsGeneratedEvent;

class CacheableContentIsGeneratedEventListener
{
    public function replaceSocialMediaWithEvent(AfterCacheableContentIsGeneratedEvent $event): void
    {
        $request = $event->getRequest();
        if (method_exists($event, 'getContent')) {
            $event->setContent($this->replaceSocialMediaInContent($event->getContent(), $request));

            return;
        }

        // TYPO3 v12/v13 compatibility: getContent()/setContent() were added in v14.
        $controller = $event->getController();
        $controller->content = $this->replaceSocialMediaInContent($controller->content, $request);
    }

    protected function replaceSocialMediaInContent(string $content, ServerRequestInterface $request): string
    {
        $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $contentObjectRenderer->setRequest($request);
        $typoScript = $request->getAttribute('frontend.typoscript')->getSetupArray();

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

                    $contentObjectRenderer->start($data, 'tt_content');
                    $handlerContent = $contentObjectRenderer->cObjGetSingle($typoScript['lib.']['socialgdpr'], $typoScript['lib.']['socialgdpr.']);
                    $content = str_replace($match->getSearch(), $handlerContent, $content);
                }
            }
        }

        return $content;
    }
}
