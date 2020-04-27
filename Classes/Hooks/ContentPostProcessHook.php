<?php
declare(strict_types = 1);
namespace IchHabRecht\SocialGdpr\Hooks;

class ContentPostProcessHook
{
    public function replaceSocialMedia(array $parameter)
    {
        $typoScriptFrontendController = $parameter['pObj'];

        $content = $typoScriptFrontendController->content;

        $typoScriptFrontendController->content = $content;
    }
}
