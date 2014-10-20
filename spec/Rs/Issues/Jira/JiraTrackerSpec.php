<?php

namespace spec\Rs\Issues\Jira;

use Jira_Api as Api; //chobie\Jira\Api;
use PhpSpec\ObjectBehavior;
use Rs\Issues\BadgeFactory;

class JiraTrackerSpec extends ObjectBehavior
{
    public function let(Api $client)
    {
        $this->beConstructedWith('https://jira.domain.com', 'foo', 'bar', $client, new BadgeFactory());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Jira\JiraTracker');
        $this->shouldHaveType('Rs\Issues\Tracker');
    }

    public function it_returns_a_Project_on_getProject(Api $client)
    {
        $client->getProject('FOOBAR')->willReturn(array('key'=>'FOOBAR'));

        $result = $this->getProject('FOOBAR');

        $result->shouldHaveType('Rs\Issues\Project');
        $result->shouldHaveType('Rs\Issues\Jira\JiraProject');
    }

    public function it_throws_an_exception_if_something_wrong_on_getProject(Api $client)
    {
        $client->getProject('FOOBAR')->willReturn();

        $this->shouldThrow('Rs\Issues\Exception\NotFoundException')->during('getProject', array('FOOBAR'));
    }

    public function it_returns_a_list_of_products_on_findProjects(Api $client)
    {
        $client->getProject('FOOBAR')->willReturn(array('key'=>'FOOBAR', 'name'=>'FOOBAR'));

        $projects = $this->findProjects('FOOBAR');

        $projects->shouldBeArray();
        $projects['FOOBAR']->shouldHaveType('Rs\Issues\Project');
        $projects['FOOBAR']->shouldHaveType('Rs\Issues\Jira\JiraProject');
    }
}
