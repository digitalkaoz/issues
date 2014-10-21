<?php

namespace spec\Rs\Issues\Utils;

use PhpSpec\ObjectBehavior;

class BadgeFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Utils\BadgeFactory');
    }

    public function it_returns_a_composer_version_badge()
    {
        $badge = $this->getComposerVersion('foo/bar');

        $badge->shouldHaveType('Rs\Issues\Badge');
    }

    public function it_returns_a_composer_downloads_badge()
    {
        $badge = $this->getComposerDownloads('foo/bar');

        $badge->shouldHaveType('Rs\Issues\Badge');
    }

    public function it_returns_a_travis_badge()
    {
        $badge = $this->getTravis('foo/bar');

        $badge->shouldHaveType('Rs\Issues\Badge');
    }

}
