<?php

namespace spec\Rs\Issues;

use PhpSpec\ObjectBehavior;

class BadgeSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('http://foo.svg', 'http://bar.com');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Badge');
    }
}
