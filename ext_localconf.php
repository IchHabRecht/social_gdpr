<?php

defined('TYPO3_MODE') || die();

call_user_func(function () {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all']['social_gdpr'] =
        \IchHabRecht\SocialGdpr\Hooks\ContentPostProcessHook::class . '->replaceSocialMedia';

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['social_gdpr']['handler'] = array_merge(
        [
            'youtube' => \IchHabRecht\SocialGdpr\Handler\YoutubeHandler::class,
            'vimeo' => \IchHabRecht\SocialGdpr\Handler\VimeoHandler::class,
            'googleMapsIframe' => \IchHabRecht\SocialGdpr\Handler\GoogleMapsIframeHandler::class,
            'openStreetMap' => \IchHabRecht\SocialGdpr\Handler\OpenStreetMapHandler::class,
        ],
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['social_gdpr']['handler'] ?? []
    );
});
