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
