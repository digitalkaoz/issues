<?php

namespace spec\Rs\Issues\Gitlab;

use Gitlab\Api\Projects;
use Gitlab\Client;
use Gitlab\HttpClient\HttpClient;
use PhpSpec\ObjectBehavior;

class GitlabTrackerSpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Gitlab\GitlabTracker');
        $this->shouldHaveType('Rs\Issues\Tracker');
    }

    public function it_is_able_to_connect_to_gitlab_with_credentials(Client $client, HttpClient $http)
    {
        $client->authenticate('foo', Client::AUTH_URL_TOKEN)->shouldBeCalled();
        $client->setBaseUrl('http://foo.com')->shouldBeCalled();
        $client->getHttpClient()->shouldBeCalled()->willReturn($http);

        $this->connect('foo', null, 'http://foo.com')->shouldReturn(true);
    }

    public function it_returns_a_Project_on_getProject(Client $client, Projects $api)
    {
        $client->api('projects')->willReturn($api);
        $api->show('foo/bar')->willReturn(array());

        $project = $this->getProject('foo/bar');

        $project->shouldHaveType('Rs\Issues\Project');
        $project->shouldHaveType('Rs\Issues\Gitlab\GitlabProject');
    }
}
