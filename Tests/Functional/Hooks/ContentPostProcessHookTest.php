<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Tests\Functional\Hooks;

use Nimut\TestingFramework\TestCase\FunctionalTestCase;

class ContentPostProcessHookTest extends FunctionalTestCase
{
    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/social_gdpr',
    ];

    /**
     * @var array
     */
    protected $configurationToUseInTestInstance = [
        'EXT' => [
            'extConf' => [
                'social_gdpr' => 'a:3:{s:14:"youtubePreview";s:1:"1";s:12:"vimeoPreview";s:1:"1";s:10:"osmPreview";s:1:"1";}',
            ],
        ],
        'EXTENSIONS' => [
            'social_gdpr' => [
                'osmPreview' => '0',
                'vimeoPreview' => '0',
                'youtubePreview' => '0',
            ],
        ],
    ];

    protected function setUp()
    {
        parent::setUp();

        $this->importDataSet('ntf://Database/pages.xml');
        $this->setUpFrontendRootPage(
            1,
            [
                'EXT:social_gdpr/Configuration/TypoScript/setup.txt',
                'EXT:social_gdpr/Tests/Functional/Fixtures/TypoScript/social_gdpr.txt',
            ]
        );
    }

    /**
     * @test
     */
    public function replaceSocialMediaReturnsPlayButtonWithAbsRefPrefix()
    {
        $response = $this->getFrontendResponse(1);

        $this->assertSame('success', $response->getStatus());

        $content = $response->getContent();

        $this->assertContains('/typo3conf/ext/social_gdpr/Resources/Public/Images/youtube_play_button.svg', $content);
        $this->assertContains('/typo3conf/ext/social_gdpr/Resources/Public/Images/play_button.svg', $content);
    }
}
