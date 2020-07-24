<?php
namespace IchHabRecht\SocialGdpr\Handler;

class Match
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $search;

    /**
     * @param string $search
     * @param array $data
     */
    public function __construct($search, array $data = [])
    {
        $this->search = $search;
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getSearch()
    {
        return $this->search;
    }
}
