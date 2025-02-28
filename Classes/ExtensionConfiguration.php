<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExtensionConfiguration implements SingletonInterface
{
    protected ?array $settings;

    public function __construct(?array $settings = null)
    {
        if ($settings === null) {
            if (class_exists(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)) {
                $settings = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('social_gdpr');
            } else {
                $settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['social_gdpr'], ['allowed_classes' => false]);
            }
        }
        $this->settings = $settings;
    }

    public function isEnabled($key): bool
    {
        return !empty($this->settings[$key]);
    }
}
