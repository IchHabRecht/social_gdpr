<?php
defined('TYPO3_MODE') || die();

call_user_func(function () {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all']['social_gdpr'] =
        \IchHabRecht\SocialGdpr\Hooks\ContentPostProcessHook::class . '->replaceSocialMedia';
});
