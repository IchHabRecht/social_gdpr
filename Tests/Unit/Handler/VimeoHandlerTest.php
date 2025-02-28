<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Tests\Unit\Handler;

use IchHabRecht\SocialGdpr\Handler\VimeoHandler;
use IchHabRecht\SocialGdpr\Service\VimeoImageService;
use PHPUnit\Framework\Attributes\Test;
use Prophecy\Prophet;

class VimeoHandlerTest extends AbstractHandler
{
    #[Test]
    public function hasMatchesFindsAllYouTubeIframes(): void
    {
        $handler = $this->getVimeoHandler();

        self::assertTrue($handler->hasMatches($this->content));
        self::assertCount(3, $handler->getMatches());
    }

    protected function getVimeoHandler(): VimeoHandler
    {
        $vimeoImageService = (new Prophet())->prophesize(VimeoImageService::class);
        $vimeoImageService->getPreviewImage('143018597')->shouldBeCalled()->willReturn('url://143018597');
        $vimeoImageService->getPreviewImage('238376269')->shouldBeCalled()->willReturn('url://238376269');
        $vimeoImageService->getPreviewImage('238386951')->shouldBeCalled()->willReturn('url://238386951');

        return new VimeoHandler($vimeoImageService->reveal());
    }
}
