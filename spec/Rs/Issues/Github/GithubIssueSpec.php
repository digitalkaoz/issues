<?php

namespace spec\Rs\Issues\Github;

use PhpSpec\ObjectBehavior;

class GithubIssueSpec extends ObjectBehavior
{
    private $data = array(
        'title'      => 'foo bar',
        'body'       => 'lorem ipsum',
        'html_url'   => 'http://foo.com',
        'state'      => 'open',
        'created_at' => '25.05.1981 13:37:42',
        'closed_at'  => null,
        'comments'   => 7,
        'pull_request' => 'foo',
        'assignee'    => array('login' => 'digitalkaoz', 'html_url' => 'http://bazz.com'),
        'user'      => array('login' => 'lolcat', 'html_url' => 'http://bar.com')
    );

    public function let()
    {
        $this->beConstructedWith($this->data);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Github\GithubIssue');
        $this->shouldHaveType('Rs\Issues\Issue');
    }

    public function it_returns_the_title()
    {
        $this->getTitle()->shouldReturn('foo bar');
    }

    public function it_returns_the_text()
    {
        $this->getText()->shouldReturn('lorem ipsum');
    }

    public function it_returns_the_url()
    {
        $this->getUrl()->shouldReturn('http://foo.com');
    }

    public function it_returns_the_state()
    {
        $this->getState()->shouldReturn('open');
    }

    public function it_returns_the_create_date_as_DateTime()
    {
        $this->getCreatedAt()->shouldHaveType('\DateTime');
    }

    public function it_returns_the_closed_date_as_DateTime_if_set()
    {
        $this->getClosedAt()->shouldReturn(null);
    }

    public function it_returns_the_comment_count()
    {
        $this->getCommentCount()->shouldReturn(7);
    }

    public function it_can_return_the_raw_data_from_github()
    {
        $this->getRaw()->shouldReturn($this->data);
    }

    public function it_returns_the_owner()
    {
        $this->getOwner()->shouldReturn('lolcat');
        $this->getOwnerUrl()->shouldReturn('http://bar.com');
    }

    public function it_returns_the_type()
    {
        $this->getType()->shouldReturn('pull');
    }

    public function it_returns_the_assignee()
    {
        $this->getAssignee()->shouldReturn('digitalkaoz');
        $this->getAssigneeUrl()->shouldReturn('http://bazz.com');
    }
}
