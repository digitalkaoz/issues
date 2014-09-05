<?php

namespace spec\Rs\Issues\Github;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GithubIssueSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(array(
            'title'      => 'foo bar',
            'body'       => 'lorem ipsum',
            'html_url'   => 'http://foo.com',
            'state'      => 'open',
            'created_at' => '25.05.1981 13:37:42',
            'closed_at'  => null,
            'comments'   => 7
        ));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Github\GithubIssue');
        $this->shouldHaveType('Rs\Issues\Issue');
    }

    function it_returns_the_title()
    {
        $this->getTitle()->shouldReturn('foo bar');
    }

    function it_returns_the_text()
    {
        $this->getText()->shouldReturn('lorem ipsum');
    }

    function it_returns_the_url()
    {
        $this->getUrl()->shouldReturn('http://foo.com');
    }

    function it_returns_the_state()
    {
        $this->getState()->shouldReturn('open');
    }

    function it_returns_the_create_date_as_DateTime()
    {
        $this->getCreatedAt()->shouldHaveType('\DateTime');
    }

    function it_returns_the_closed_date_as_DateTime_if_set()
    {
        $this->getClosedAt()->shouldReturn(null);
    }

    function it_returns_the_comment_count()
    {
        $this->getCommentCount()->shouldReturn(7);
    }

    function it_can_return_the_raw_data_from_github()
    {
        $this->getRaw()->shouldReturn(array(
            'title'      => 'foo bar',
            'body'       => 'lorem ipsum',
            'html_url'   => 'http://foo.com',
            'state'      => 'open',
            'created_at' => '25.05.1981 13:37:42',
            'closed_at'  => null,
            'comments'   => 7
        ));
    }

}
