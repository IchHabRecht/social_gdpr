<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExtensionConfiguration implements SingletonInterface
{
    /**
     * @var array
     */
    protected $settings = [];

    public function __construct(array $settings = null)
    {
        if ($settings === null) {
            $settings = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('social_gdpr');
        }
        $this->settings = $settings;
    }

    public function isEnabled($key)
    {
        return !empty($this->settings[$key]);
    }
}
