<?php

namespace spec\Rs\Issues\Gitlab;

use Gitlab\Client;
use PhpSpec\ObjectBehavior;

class GitlabProjectSpec extends ObjectBehavior
{
    private $data = array(
        'path_with_namespace' => 'foo/bar',
        'description'         => 'lorem ipsum',
        'web_url'             => 'http://foo.com'
    );

    public function let(Client $client)
    {
        $this->beConstructedWith($this->data, $client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Gitlab\GitlabProject');
        $this->shouldHaveType('Rs\Issues\Project');
    }

    public function it_returns_the_type()
    {
        $this->getType()->shouldReturn('gitlab');
    }

    public function it_returns_the_fullname()
    {
        $this->getName()->shouldReturn('foo/bar');
    }

    public function it_returns_the_description()
    {
        $this->getDescription()->shouldReturn('lorem ipsum');
    }

    public function it_returns_the_url()
    {
        $this->getUrl()->shouldReturn('http://foo.com');
    }
}
