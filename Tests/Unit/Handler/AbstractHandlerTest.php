<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Tests\Unit\Handler;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

abstract class AbstractHandlerTest extends UnitTestCase
{
    /**
     * @var string
     */
    protected $content;

    protected function setUp(): void
    {
        parent::setUp();

        $this->content = file_get_contents(__DIR__ . '/../../Fixtures/Content.html');
    }
}
