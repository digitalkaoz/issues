<?php

namespace spec\Rs\Issues\Gitlab;

use Gitlab\Api\Projects;
use Gitlab\Client;
use PhpSpec\ObjectBehavior;

class GitlabTrackerSpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $client->authenticate('foo', 'url_token')->shouldBeCalled();

        $this->beConstructedWith('http://foo.com', 'foo', $client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Gitlab\GitlabTracker');
        $this->shouldHaveType('Rs\Issues\Tracker');
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
