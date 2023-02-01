<?php

declare(strict_types=1);
defined('TYPO3') or die();

call_user_func(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'social_gdpr',
        'Configuration/TypoScript/',
        'Social GDPR'
    );
});
