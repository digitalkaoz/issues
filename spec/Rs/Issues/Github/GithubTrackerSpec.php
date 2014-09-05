<?php

namespace spec\Rs\Issues\Github;

use Github\Api\Repo;
use Github\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GithubTrackerSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Github\GithubTracker');
        $this->shouldHaveType('Rs\Issues\Tracker');
    }

    function it_is_able_to_connect_to_github_without_credentials(Client $client)
    {
        $client->authenticate()->shouldNotBeCalled();

        $this->connect()->shouldReturn(true);
    }

    function it_is_able_to_connect_to_github_with_credentials(Client $client)
    {
        $client->authenticate('foo', null, Client::AUTH_HTTP_PASSWORD)->shouldBeCalled();

        $this->connect('foo')->shouldReturn(true);
    }

    function it_returns_a_Project_on_getProject(Client $client, Repo $api)
    {
        $client->repos()->willReturn($api);
        $api->show('foo', 'bar')->willReturn(array());


        $project = $this->getProject('foo/bar');

        $project->shouldHaveType('Rs\Issues\Project');
        $project->shouldHaveType('Rs\Issues\Github\GithubProject');
    }
}
