<?php
declare(strict_types = 1);
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

        $this->content = '<div class="embed embed-responsive embed-responsive-16by9">
            <iframe src="https://www.youtube-nocookie.com/embed/yiJjpKzCVE4?autohide=1&amp;controls=1&amp;enablejsapi=1&amp;origin=http%3A%2F%2F8-7.typo3.test" allowfullscreen class="embed-responsive-item" title="Grafbonze ist geschrumpft! | Minecraft | Deutsch HD" allow="fullscreen"></iframe>
        </div>
        <div id="c227" class="frame frame-default frame-type-html frame-layout-0 frame-space-before-none frame-space-after-none">
            <iframe width="560" height="315" src="https://www.youtube.com/watch?v=LMx4SmK4s0U&controls=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <div id="c228" class="frame frame-default frame-type-html frame-layout-0 frame-space-before-none frame-space-after-none">
            <iframe width="560" height="315" src="//www.youtube-nocookie.com/embed/9zoHWNR5OcQ" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <div class="embed embed-responsive embed-responsive-16by9">
            <iframe src="https://player.vimeo.com/video/143018597" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
        </div>
        <div class="embed embed-responsive embed-responsive-16by9">
            <iframe src="https://player.vimeo.com/video/238376269?title=0&amp;byline=0&amp;portrait=0" allowfullscreen="" class="embed-responsive-item" title="TYPO3 - Still Here" allow="fullscreen"></iframe>
        </div>
        <div id="c235" class="frame frame-default frame-type-html frame-layout-0 frame-space-before-none frame-space-after-none">
            <div style="padding:56.25% 0 0 0;position:relative;">
                <iframe src="https://player.vimeo.com/video/238386951" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen" allowfullscreen=""></iframe>
            </div>
            <p><a href="//vimeo.com/238386951">Why choose TYPO3</a> from <a href="https://vimeo.com/studioflox">studioflox</a> on <a href="https://vimeo.com">Vimeo</a>.</p>
        </div>';
    }
}
