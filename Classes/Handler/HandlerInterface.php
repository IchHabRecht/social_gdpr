<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Handler;

interface HandlerInterface
{
    public function hasMatches(string $content): bool;

    /**
     * @return ContentMatch[]
     */
    public function getMatches(): array;
}
