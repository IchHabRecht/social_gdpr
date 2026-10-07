<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Tests\Unit\Handler;

use IchHabRecht\SocialGdpr\Handler\GoogleMapsIframeHandler;
use PHPUnit\Framework\Attributes\Test;

class GoogleMapsIframeHandlerTest extends AbstractHandler
{
    #[Test]
    public function hasMatchesFindsAllGoogleMapsIframes(): void
    {
        $handler = new GoogleMapsIframeHandler();

        $this->assertTrue($handler->hasMatches($this->content));
        $this->assertCount(1, $handler->getMatches());
    }
}
