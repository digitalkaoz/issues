<?php

namespace spec\Rs\Issues\Github;

use Github\Api\Repo;
use Github\Api\User;
use Github\Client;
use PhpSpec\ObjectBehavior;

class GithubTrackerSpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $client->authenticate('foo', null, 'http_password')->shouldBeCalled();

        $this->beConstructedWith('foo', $client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Github\GithubTracker');
        $this->shouldHaveType('Rs\Issues\Tracker');
    }

    public function it_returns_a_Project_on_getProject(Client $client, Repo $api)
    {
        $client->repos()->willReturn($api);
        $api->show('foo', 'bar')->willReturn(array());

        $project = $this->getProject('foo/bar');

        $project->shouldHaveType('Rs\Issues\Project');
        $project->shouldHaveType('Rs\Issues\Github\GithubProject');
    }

    public function it_returns_a_list_of_Projects_on_findProjects(Client $client, Repo $repoApi, User $userApi)
    {
        $client->repos()->willReturn($repoApi);
        $repoApi->show('foo', 'bar')->willReturn(array('full_name'=>'foo/bar'));

        $client->user()->willReturn($userApi);
        $userApi->repositories('foo')->willReturn(array(array('full_name'=>'foo/bar')));

        $projects = $this->findProjects('foo/[bar|bazz]+$');

        $projects->shouldBeArray();
        $projects['foo/bar']->shouldHaveType('Rs\Issues\Project');
        $projects['foo/bar']->shouldHaveType('Rs\Issues\Github\GithubProject');
    }
}
