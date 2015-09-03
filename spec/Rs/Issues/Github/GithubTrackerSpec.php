<?php

namespace spec\Rs\Issues\Github;

use Github\Api\Repo;
use Github\Api\User;
use Github\Client;
use PhpSpec\ObjectBehavior;
use Rs\Issues\Utils\RepositoryParser;

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
        $api->show('foo', 'bar')->willReturn([]);

        $project = $this->getProject('foo/bar');

        $project->shouldHaveType('Rs\Issues\Project');
        $project->shouldHaveType('Rs\Issues\Github\GithubProject');
    }

    public function it_throws_an_exception_for_invalid_projects(Client $client, Repo $api)
    {
        $client->repos()->willReturn($api);
        $api->show('foo', 'bar')->willThrow(new \RuntimeException());

        $this->shouldThrow('Rs\Issues\Exception\NotFoundException')->during('getProject', ['foo/bar']);
    }

    public function it_throws_an_exception_for_invalid_project_names(RepositoryParser $repositoryParser)
    {
        $repositoryParser->isConcrete('foo')->shouldBeCalled()->willReturn(false);
        $this->setRepositoryParser($repositoryParser);

        $this->shouldThrow('\InvalidArgumentException')->during('getProject', ['foo']);
    }

    public function it_returns_a_list_of_Projects_on_findProjects(Client $client, Repo $repoApi, User $userApi)
    {
        $client->repos()->willReturn($repoApi);
        $repoApi->show('foo', 'bar')->willReturn(['full_name' => 'foo/bar']);

        $client->user()->willReturn($userApi);
        $userApi->repositories('foo')->willReturn([['full_name' => 'foo/bar']]);

        $projects = $this->findProjects('foo/[bar|bazz]+$');

        $projects->shouldBeArray();
        $projects['foo/bar']->shouldHaveType('Rs\Issues\Project');
        $projects['foo/bar']->shouldHaveType('Rs\Issues\Github\GithubProject');
    }
}
