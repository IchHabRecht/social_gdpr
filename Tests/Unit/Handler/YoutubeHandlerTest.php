<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Tests\Unit\Handler;

use IchHabRecht\SocialGdpr\Handler\YoutubeHandler;
use IchHabRecht\SocialGdpr\Service\YoutubeImageService;
use PHPUnit\Framework\Attributes\Test;
use Prophecy\Prophet;

class YoutubeHandlerTest extends AbstractHandler
{
    #[Test]
    public function hasMatchesFindsAllYouTubeIframes(): void
    {
        $handler = $this->getYoutubeHandler();

        self::assertTrue($handler->hasMatches($this->content));
        self::assertCount(3, $handler->getMatches());
    }

    protected function getYoutubeHandler(): YoutubeHandler
    {
        $youtubeImageService = (new Prophet())->prophesize(YoutubeImageService::class);
        $youtubeImageService->getPreviewImage('yiJjpKzCVE4')->shouldBeCalled()->willReturn('url://yiJjpKzCVE4');
        $youtubeImageService->getPreviewImage('LMx4SmK4s0U')->shouldBeCalled()->willReturn('url://LMx4SmK4s0U');
        $youtubeImageService->getPreviewImage('9zoHWNR5OcQ')->shouldBeCalled()->willReturn('url://9zoHWNR5OcQ');

        return new YoutubeHandler($youtubeImageService->reveal());
    }
}
