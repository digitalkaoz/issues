<?php

namespace spec\Rs\Issues\Jira;

use Jira_Issue as Issue; //chobie\Jira\Issue;
use PhpSpec\ObjectBehavior;

class JiraIssueSpec extends ObjectBehavior
{
    public function let(Issue $issue)
    {
        $this->beConstructedWith($issue);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Jira\JiraIssue');
        $this->shouldHaveType('Rs\Issues\Issue');
    }

    public function it_returns_its_url(Issue $issue)
    {
        $url = 'http://jira.google.com/issues/71';
        $issue->getSelf()->shouldBeCalled()->willReturn($url);

        $this->getUrl()->shouldBe($url);
    }

    public function it_returns_its_title(Issue $issue)
    {
        $issue->getSummary()->shouldBeCalled()->willReturn('foo');

        $this->getTitle()->shouldBe('foo');
    }

    public function it_returns_its_description(Issue $issue)
    {
        $issue->getDescription()->shouldBeCalled()->willReturn('bar');

        $this->getDescription()->shouldBe('bar');
    }

    public function it_returns_the_state(Issue $issue)
    {
        $issue->getStatus()->shouldBeCalled()->willReturn(['name' => 'open']);

        $this->getState()->shouldReturn('open');
    }

    public function it_returns_the_type(Issue $issue)
    {
        $issue->getIssueType()->shouldBeCalled()->willReturn(['name' => 'issue']);

        $this->getType()->shouldReturn('issue');
    }

    public function it_returns_the_create_date_as_DateTime(Issue $issue)
    {
        $issue->getCreated()->shouldBeCalled()->willReturn('25.05.1981 13:37:42');

        $this->getCreatedAt()->shouldHaveType('\DateTime');
    }

    public function it_returns_the_closed_date_as_DateTime_if_set(Issue $issue)
    {
        $issue->getResolutionDate()->shouldBeCalled()->willReturn(null);

        $this->getClosedAt()->shouldReturn(null);
    }

    public function it_returns_the_updated_date_as_DateTime_if_set(Issue $issue)
    {
        $issue->getUpdated()->shouldBeCalled()->willReturn('25.05.1981 13:37:42');

        $this->getUpdatedAt()->shouldHaveType('\DateTime');
    }

    public function it_returns_the_comment_count(Issue $issue)
    {
        $issue->get('comment')->shouldBeCalled()->willReturn(['total' => 7]);

        $this->getCommentCount()->shouldReturn(7);
    }

    public function it_returns_the_id(Issue $issue)
    {
        $issue->getKey()->shouldBeCalled()->willReturn('FOO-7');

        $this->getId()->shouldReturn('FOO-7');
    }

    public function it_returns_the_author(Issue $issue)
    {
        $issue->getReporter()->shouldBeCalled()->willReturn(['displayName' => 'foo']);

        $this->getAuthor()->shouldReturn('foo');
    }

    public function it_returns_the_author_url(Issue $issue)
    {
        $issue->getReporter()->shouldBeCalled()->willReturn(['displayName' => 'foo']);
        $issue->getSelf()->shouldBeCalled()->willReturn('http://jira.com');

        $this->getAuthorUrl()->shouldReturn('http://jira.com/ViewProfile.jspa?name=foo');
    }

    public function it_returns_the_assignee(Issue $issue)
    {
        $issue->getAssignee()->shouldBeCalled()->willReturn(['displayName' => 'foo']);

        $this->getAssignee()->shouldReturn('foo');
    }

    public function it_returns_the_assignee_url(Issue $issue)
    {
        $issue->getAssignee()->shouldBeCalled()->willReturn(['displayName' => 'foo']);
        $issue->getSelf()->shouldBeCalled()->willReturn('http://jira.com');

        $this->getAssigneeUrl()->shouldReturn('http://jira.com/ViewProfile.jspa?name=foo');
    }

    public function it_doenst_returns_the_assignee_url_if_no_assignee(Issue $issue)
    {
        $issue->getAssignee()->shouldBeCalled()->willReturn();
        $issue->getSelf()->shouldNotBeCalled();

        $this->getAssigneeUrl()->shouldReturn(null);
    }

    public function it_returns_its_tags(Issue $issue)
    {
        $issue->getLabels()->shouldBeCalled()->willReturn(['foo', 'bar']);

        $this->getTags()->shouldReturn(['foo', 'bar']);
    }
}
