<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Tests\Unit\Handler;

use IchHabRecht\SocialGdpr\Handler\YoutubeHandler;
use IchHabRecht\SocialGdpr\Service\YoutubeImageService;

class YoutubeHandlerTest extends AbstractVideoTest
{
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
