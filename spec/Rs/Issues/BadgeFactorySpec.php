<?php

namespace spec\Rs\Issues;

use PhpSpec\ObjectBehavior;

class BadgeFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\BadgeFactory');
    }

    public function it_returns_a_composer_version_badge()
    {
        $badge = $this->getComposerVersion('foo/bar');

        $badge->shouldHaveType('Rs\Issues\Badge');
        $badge->toArray()->shouldBe(array(
            'img'  => 'https://poser.pugx.org/foo/bar/version.svg',
            'link' => 'https://packagist.org/packages/foo/bar'
        ));
    }

    public function it_returns_a_composer_downloads_badge()
    {
        $badge = $this->getComposerDownloads('foo/bar');

        $badge->shouldHaveType('Rs\Issues\Badge');
        $badge->toArray()->shouldBe(array(
            'img'  => 'https://poser.pugx.org/foo/bar/d/total.svg',
            'link' => 'https://packagist.org/packages/foo/bar'
        ));
    }

    public function it_returns_a_travis_badge()
    {
        $badge = $this->getTravis('foo/bar');

        $badge->shouldHaveType('Rs\Issues\Badge');
        $badge->toArray()->shouldBe(array(
            'img'  => 'https://travis-ci.org/foo/bar.svg',
            'link' => 'https://travis-ci.org/foo/bar'
        ));
    }

}
