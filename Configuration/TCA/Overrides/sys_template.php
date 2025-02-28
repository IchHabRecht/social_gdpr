<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

ExtensionManagementUtility::addStaticFile(
    'social_gdpr',
    'Configuration/TypoScript/',
    'Social GDPR'
);
