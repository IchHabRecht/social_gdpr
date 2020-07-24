<?php
namespace IchHabRecht\SocialGdpr\Handler;

interface HandlerInterface
{
    /**
     * @param string $content
     * @return bool
     */
    public function hasMatches($content);

    /**
     * @return Match[]
     */
    public function getMatches();
}
