<?php

defined('TYPO3') || die('Access denied.');

call_user_func(function () {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms_inline.php']['tceformsInlineHook'][] =
        \IchHabRecht\SocialGdpr\Form\CustomInlineControl\OnlineFalMediaPreviewFlush::class;

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
