<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Tests\Unit\Handler;

use Nimut\TestingFramework\TestCase\UnitTestCase;

abstract class AbstractHandlerTest extends UnitTestCase
{
    /**
     * @var string
     */
    protected $content;

    protected function setUp(): void
    {
        parent::setUp();

        // $extensionConfiguration = [
        //     'osmPreview' => '1',
        //     'vimeoPreview' => '1',
        //     'youtubePreview' => '1',
        // ];
        // $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['social_gdpr'] = $extensionConfiguration;
        // $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['social_gdpr'] = serialize($extensionConfiguration);

        $this->content = file_get_contents(__DIR__ . '/../../Fixtures/Content.html');
    }
}
