<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Tests\Unit\Handler;

use IchHabRecht\SocialGdpr\Handler\VimeoHandler;
use IchHabRecht\SocialGdpr\Service\VimeoImageService;

class VimeoHandlerTest extends AbstractVideoTest
{
    /**
     * @test
     */
    public function hasMatchesFindsAllYouTubeIframes()
    {
        $handler = $this->getVimeoHandler();

        $this->assertTrue($handler->hasMatches($this->content));
        $this->assertCount(3, $handler->getMatches());
    }

    protected function getVimeoHandler(): VimeoHandler
    {
        $vimeoImageService = $this->prophesize(VimeoImageService::class);
        $vimeoImageService->getPreviewImage('143018597')->shouldBeCalled()->willReturn('url://143018597');
        $vimeoImageService->getPreviewImage('238376269')->shouldBeCalled()->willReturn('url://238376269');
        $vimeoImageService->getPreviewImage('238386951')->shouldBeCalled()->willReturn('url://238386951');

        return new VimeoHandler($vimeoImageService->reveal());
    }
}
