<?php

namespace spec\Rs\Issues\Jira;

use chobie\Jira\Api;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JiraTrackerSpec extends ObjectBehavior
{
    function let(Api $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Jira\JiraTracker');
        $this->shouldHaveType('Rs\Issues\Tracker');
    }

    function it_can_connect()
    {
        $this->connect('foo', 'bar', 'https://jira.domain.com')->shouldReturn(true);
    }

    function it_returns_a_Project_on_getProject(Api $client)
    {
        $client->getProject('FOOBAR')->willReturn(array());

        $result = $this->getProject('FOOBAR');

        $result->shouldHaveType('Rs\Issues\Project');
        $result->shouldHaveType('Rs\Issues\Jira\JiraProject');
    }
}
