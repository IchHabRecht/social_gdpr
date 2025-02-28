<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Handler;

class ContentMatch
{
    public function __construct(protected string $search, protected array $data = [])
    {
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getSearch(): string
    {
        return $this->search;
    }
}
