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
        $api->show('foo/bar')->willReturn([]);

        $project = $this->getProject('foo/bar');

        $project->shouldHaveType('Rs\Issues\Project');
        $project->shouldHaveType('Rs\Issues\Gitlab\GitlabProject');
    }

    public function it_returns_a_list_of_Projects_on_findProjects(Client $client, Projects $repoApi)
    {
        $client->api('projects')->willReturn($repoApi);
        $repoApi->show('foo/bar')->willReturn(['path_with_namespace' => 'foo/bar']);
        $repoApi->accessible(1, 9999)->willReturn([['path_with_namespace' => 'foo/bar']]);

        $projects = $this->findProjects('foo/(?!bazz|lol)([a-z0-9\.-]+)$');

        $projects->shouldBeArray();
        $projects['foo/bar']->shouldHaveType('Rs\Issues\Project');
        $projects['foo/bar']->shouldHaveType('Rs\Issues\Gitlab\GitlabProject');
    }
}
