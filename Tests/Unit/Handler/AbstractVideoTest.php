<?php
namespace IchHabRecht\SocialGdpr\Tests\Unit\Handler;

use Nimut\TestingFramework\TestCase\UnitTestCase;

abstract class AbstractVideoTest extends UnitTestCase
{
    /**
     * @var string
     */
    protected $content;

    protected function setUp()
    {
        parent::setUp();

        $this->content = file_get_contents(__DIR__ . '/../../Fixtures/Content.html');
    }
}
