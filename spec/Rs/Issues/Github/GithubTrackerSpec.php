<?php

namespace spec\Rs\Issues\Github;

use Github\Api\Repo;
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
}
