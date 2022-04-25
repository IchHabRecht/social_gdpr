<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Handler;

class ContentMatch
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $search;

    public function __construct(string $search, array $data = [])
    {
        $this->search = $search;
        $this->data = $data;
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
