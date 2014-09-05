<?php

namespace spec\Rs\Issues\Jira;

use chobie\Jira\Issue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JiraIssueSpec extends ObjectBehavior
{
    function let(Issue $issue)
    {
        $this->beConstructedWith($issue);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Jira\JiraIssue');
        $this->shouldHaveType('Rs\Issues\Issue');
    }

    function it_returns_its_url(Issue $issue)
    {
        $url = 'http://jira.google.com/issues/71';
        $issue->getSelf()->shouldBeCalled()->willReturn($url);

        $this->getUrl()->shouldBe($url);
    }

    function it_returns_its_title(Issue $issue)
    {
        $issue->getSummary()->shouldBeCalled()->willReturn('foo');

        $this->getTitle()->shouldBe('foo');
    }

    function it_returns_its_text(Issue $issue)
    {
        $issue->getDescription()->shouldBeCalled()->willReturn('bar');

        $this->getText()->shouldBe('bar');
    }

    function it_returns_the_state(Issue $issue)
    {
        $issue->getStatus()->shouldBeCalled()->willReturn(array('name'=>'open'));

        $this->getState()->shouldReturn('open');
    }

    function it_returns_the_create_date_as_DateTime(Issue $issue)
    {
        $issue->getCreated()->shouldBeCalled()->willReturn('25.05.1981 13:37:42');

        $this->getCreatedAt()->shouldHaveType('\DateTime');
    }

    function it_returns_the_closed_date_as_DateTime_if_set(Issue $issue)
    {
        $issue->getResolutionDate()->shouldBeCalled()->willReturn(null);

        $this->getClosedAt()->shouldReturn(null);
    }

    function it_returns_the_comment_count(Issue $issue)
    {
        $issue->get('comment')->shouldBeCalled()->willReturn(array('total'=>7));

        $this->getCommentCount()->shouldReturn(7);
    }

}
