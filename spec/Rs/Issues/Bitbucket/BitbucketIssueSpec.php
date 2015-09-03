<?php

namespace spec\Rs\Issues\Bitbucket;

use PhpSpec\ObjectBehavior;

class BitbucketIssueSpec extends ObjectBehavior
{
    private $data = [
        'title'            => 'foo bar',
        'content'          => 'lorem ipsum',
        'state'            => 'open',
        'created_on'       => '25.05.1981 13:37:42',
        'utc_last_updated' => '14.05.2013 07:05:00',
        'closed_on'        => null,
        'comment_count'    => 7,
        'pull_request'     => 'foo',
        'responsible'      => ['username' => 'digitalkaoz'],
        'reported_by'      => ['username' => 'lolcat'],
        'local_id'         => 1337,
    ];

    public function let()
    {
        $this->beConstructedWith($this->data, 'issue', 'http://foo.com/lolcat/bar');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Bitbucket\BitbucketIssue');
        $this->shouldHaveType('Rs\Issues\Issue');
    }

    public function it_returns_the_title()
    {
        $this->getTitle()->shouldReturn('foo bar');
    }

    public function it_returns_the_description()
    {
        $this->getDescription()->shouldReturn('lorem ipsum');
    }

    public function it_returns_the_url()
    {
        $this->getUrl()->shouldReturn('http://foo.com/lolcat/bar/issue/1337');
    }

    public function it_returns_the_state()
    {
        $this->getState()->shouldReturn('open');
    }

    public function it_returns_the_create_date_as_DateTime()
    {
        $this->getCreatedAt()->shouldHaveType('\DateTime');
    }

    public function it_returns_the_updated_date_as_DateTime()
    {
        $this->getUpdatedAt()->shouldHaveType('\DateTime');
    }

    public function it_returns_the_closed_date_as_DateTime_if_set()
    {
        $this->getClosedAt()->shouldReturn(null);
    }

    public function it_returns_the_comment_count()
    {
        $this->getCommentCount()->shouldReturn(7);
    }

    public function it_returns_the_author()
    {
        $this->getAuthor()->shouldReturn('lolcat');
        $this->getAuthorUrl()->shouldReturn('http://foo.com/lolcat');
    }

    public function it_returns_the_type()
    {
        $this->getType()->shouldReturn('issue');
    }

    public function it_returns_the_assignee()
    {
        $this->getAssignee()->shouldReturn('digitalkaoz');
        $this->getAssigneeUrl()->shouldReturn('http://foo.com/digitalkaoz');
    }

    public function it_returns_its_tags()
    {
        $this->getTags()->shouldBe([]);
    }

    public function it_returns_its_id()
    {
        $this->getId()->shouldBe(1337);
    }
}
