<?php

namespace spec\Rs\Issues\Github;

use Github\Api\Repo;
use Github\Client;
use PhpSpec\ObjectBehavior;

class GithubTrackerSpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Github\GithubTracker');
        $this->shouldHaveType('Rs\Issues\Tracker');
    }

    public function it_is_able_to_connect_to_github_without_credentials(Client $client)
    {
        $client->authenticate()->shouldNotBeCalled();

        $this->connect()->shouldReturn(true);
    }

    public function it_is_able_to_connect_to_github_with_credentials(Client $client)
    {
        $client->authenticate('foo', null, Client::AUTH_HTTP_PASSWORD)->shouldBeCalled();

        $this->connect('foo')->shouldReturn(true);
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
