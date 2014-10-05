<?php

namespace spec\Rs\Issues\Jira;

use chobie\Jira\Api;
use PhpSpec\ObjectBehavior;

class JiraTrackerSpec extends ObjectBehavior
{
    public function let(Api $client)
    {
        $this->beConstructedWith('https://jira.domain.com', 'foo', 'bar', $client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Jira\JiraTracker');
        $this->shouldHaveType('Rs\Issues\Tracker');
    }

    public function it_returns_a_Project_on_getProject(Api $client)
    {
        $client->getProject('FOOBAR')->willReturn(array());

        $result = $this->getProject('FOOBAR');

        $result->shouldHaveType('Rs\Issues\Project');
        $result->shouldHaveType('Rs\Issues\Jira\JiraProject');
    }
}
