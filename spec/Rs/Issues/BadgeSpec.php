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

    public function it_returns_an_array_representation()
    {
        $this->toArray()->shouldBe(array('img' => 'http://foo.svg', 'link' => 'http://bar.com'));
    }
}
