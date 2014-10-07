<?php

namespace spec\Rs\Issues\Jira;

use Jira_Api as Api; //chobie\Jira\Api;
use Jira_Api_Result as Result; //chobie\Jira\Api\Api\Result;
use PhpSpec\ObjectBehavior;
use Rs\Issues\BadgeFactory;

class JiraProjectSpec extends ObjectBehavior
{
    public function let(Api $client)
    {
        $this->beConstructedWith(array(
            'name'        => 'foo',
            'description' => 'bar',
            'key'         => 'FOOBAR'
        ), $client, new BadgeFactory());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Jira\JiraProject');
        $this->shouldHaveType('Rs\Issues\Project');
    }

    public function it_returns_its_name()
    {
        $this->getName()->shouldBe('foo');
    }

    public function it_returns_its_description()
    {
        $this->getDescription()->shouldBe('bar');
    }

    public function it_returns_the_type()
    {
        $this->getType()->shouldReturn('jira');
    }

    public function it_returns_its_issues(Api $client, Result $result)
    {
        $result->beConstructedWith(array('startAt' => 0, 'maxResults' => 1, 'total' => 1, 'issues' => array(array())));

        $client->search("project = FOOBAR AND status != closed AND status != resolved", 0, 50, null)->shouldBeCalled()->willReturn($result);
        $result = $this->getIssues();

        $result->shouldBeArray();
    }

    public function it_returns_empty_badges()
    {
        $this->getBadges()->shouldBeArray();
        $this->getBadges()->shouldHaveCount(0);
    }
}
