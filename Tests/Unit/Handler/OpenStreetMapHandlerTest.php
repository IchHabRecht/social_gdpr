<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Tests\Unit\Handler;

use IchHabRecht\SocialGdpr\Handler\OpenStreetMapHandler;
use IchHabRecht\SocialGdpr\Service\OpenStreetMapService;

class OpenStreetMapHandlerTest extends AbstractHandlerTest
{
    /**
     * @test
     */
    public function hasMatchesFindsAllYouTubeIframes()
    {
        $handler = $this->getOpenStreetMapHandler();

        $this->assertTrue($handler->hasMatches($this->content));
        $this->assertCount(1, $handler->getMatches());
    }

    protected function getOpenStreetMapHandler(): OpenStreetMapHandler
    {
        $openStreetMapService = $this->prophesize(OpenStreetMapService::class);
        $openStreetMapService->getPreviewImage('6.737816333770753%2C51.24353916815029%2C6.741700172424317%2C51.24519640352675')->shouldBeCalled()->willReturn('url://bbox');

        return new OpenStreetMapHandler($openStreetMapService->reveal());
    }
}
