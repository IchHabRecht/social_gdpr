<?php
declare(strict_types = 1);
namespace IchHabRecht\SocialGdpr\Handler;

use IchHabRecht\SocialGdpr\Service\YoutubeImageService;
use Nimut\TestingFramework\TestCase\UnitTestCase;

class YoutubeHandlerTest extends UnitTestCase
{
    /**
     * @var string
     */
    protected $content;

    protected function setUp()
    {
        parent::setUp();

        $this->content = '<div class="embed embed-responsive embed-responsive-16by9">
            <iframe src="https://www.youtube-nocookie.com/embed/yiJjpKzCVE4?autohide=1&amp;controls=1&amp;enablejsapi=1&amp;origin=http%3A%2F%2F8-7.typo3.test" allowfullscreen class="embed-responsive-item" title="Grafbonze ist geschrumpft! | Minecraft | Deutsch HD" allow="fullscreen"></iframe>
        </div>
        <div id="c227" class="frame frame-default frame-type-html frame-layout-0 frame-space-before-none frame-space-after-none">
            <iframe width="560" height="315" src="https://www.youtube.com/watch?v=LMx4SmK4s0U&controls=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <div id="c228" class="frame frame-default frame-type-html frame-layout-0 frame-space-before-none frame-space-after-none">
            <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/9zoHWNR5OcQ" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>';
    }

    /**
     * @test
     */
    public function hasMatchesFindsAllYouTubeIframes()
    {
        $handler = $this->getYoutubeHandler();

        $this->assertTrue($handler->hasMatches($this->content));
        $this->assertCount(3, $handler->getMatches());
    }

    protected function getYoutubeHandler(): YoutubeHandler
    {
        $youtubeImageService = $this->prophesize(YoutubeImageService::class);
        $youtubeImageService->getPreviewImage('yiJjpKzCVE4')->shouldBeCalled()->willReturn('url://yiJjpKzCVE4');
        $youtubeImageService->getPreviewImage('LMx4SmK4s0U')->shouldBeCalled()->willReturn('url://LMx4SmK4s0U');
        $youtubeImageService->getPreviewImage('9zoHWNR5OcQ')->shouldBeCalled()->willReturn('url://9zoHWNR5OcQ');

        return new YoutubeHandler($youtubeImageService->reveal());
    }
}
