<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Tests\Functional\Hooks;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class ContentPostProcessHookTest extends FunctionalTestCase
{
    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->testExtensionsToLoad = [
            'typo3conf/ext/social_gdpr',
        ];

        $this->configurationToUseInTestInstance = [
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
    }

    protected function setUp(): void
    {
        parent::setUp();

        $fixturePath = ORIGINAL_ROOT . 'typo3conf/ext/social_gdpr/Tests/Functional/Fixtures/Database/';
        $this->importCSVDataSet($fixturePath . 'pages.csv');

        $this->setUpFrontendPage(
            1,
            [
                'EXT:social_gdpr/Configuration/TypoScript/setup.typoscript',
                'EXT:social_gdpr/Tests/Functional/Fixtures/TypoScript/social_gdpr.typoscript',
            ]
        );
    }

    /**
     * @test
     */
    public function replaceSocialMediaReturnsPlayButtonWithAbsRefPrefix()
    {
        $request = new InternalRequest('http://localhost/');

        if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() < 11) {
            $response = $this->executeFrontendRequest($request);
        } else {
            $response = $this->executeFrontendSubRequest($request);
        }

        $this->assertEquals(200, $response->getStatusCode());

        $content = (string)$response->getBody();

        $this->assertStringContainsString('/typo3conf/ext/social_gdpr/Resources/Public/Images/youtube_play_button.svg', $content);
        $this->assertStringContainsString('/typo3conf/ext/social_gdpr/Resources/Public/Images/play_button.svg', $content);
    }

    protected function setUpFrontendPage($pageId, array $typoScriptFiles = [], array $templateValues = [])
    {
        parent::setUpFrontendRootPage($pageId, $typoScriptFiles, $templateValues);

        $path = Environment::getConfigPath() . '/sites/page_' . $pageId . '/';
        $target = $path . 'config.yaml';
        $file = ORIGINAL_ROOT . 'typo3conf/ext/social_gdpr/Tests/Functional/Fixtures/Frontend/site.yaml';
        if (!file_exists($target)) {
            GeneralUtility::mkdir_deep($path);
            $fileContent = file_get_contents($file);
            $fileContent = str_replace('\'{rootPageId}\'', (string)$pageId, $fileContent);
            GeneralUtility::writeFile($target, $fileContent);
        }
    }
}
