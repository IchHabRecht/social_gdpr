<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Tests\Unit\Handler;

use IchHabRecht\SocialGdpr\Handler\OpenStreetMapHandler;
use IchHabRecht\SocialGdpr\Service\OpenStreetMapService;
use PHPUnit\Framework\Attributes\Test;
use Prophecy\Prophet;

class OpenStreetMapHandlerTest extends AbstractHandler
{
    #[Test]
    public function hasMatchesFindsAllYouTubeIframes(): void
    {
        $handler = $this->getOpenStreetMapHandler();

        self::assertTrue($handler->hasMatches($this->content));
        self::assertCount(1, $handler->getMatches());
    }

    protected function getOpenStreetMapHandler(): OpenStreetMapHandler
    {
        $openStreetMapService = (new Prophet())->prophesize(OpenStreetMapService::class);
        $openStreetMapService->getPreviewImage('6.737816333770753%2C51.24353916815029%2C6.741700172424317%2C51.24519640352675')->shouldBeCalled()->willReturn('url://bbox');

        return new OpenStreetMapHandler($openStreetMapService->reveal());
    }
}
