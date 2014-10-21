<?php

namespace spec\Rs\Issues\Bitbucket;

use Bitbucket\API\Api;
use Bitbucket\API\Repositories;
use Bitbucket\API\Repositories\Repository;
use Buzz\Message\MessageInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BitbucketTrackerSpec extends ObjectBehavior
{
    public function let(Api $client)
    {
        $client->setCredentials(Argument::type('Bitbucket\API\Authentication\AuthenticationInterface'))->shouldBeCalled();

        $this->beConstructedWith('foo', 'password', $client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Bitbucket\BitbucketTracker');
        $this->shouldHaveType('Rs\Issues\Tracker');
    }

    public function it_returns_a_Project_on_getProject(Api $client, Repository $api, MessageInterface $response)
    {
        $client->api('Repositories\Repository')->willReturn($api);
        $api->get('foo', 'bar')->willReturn($response);

        $response->getContent()->willReturn('{}');

        $project = $this->getProject('foo/bar');

        $project->shouldHaveType('Rs\Issues\Project');
        $project->shouldHaveType('Rs\Issues\Bitbucket\BitbucketProject');
    }

    public function it_returns_a_list_of_Projects_on_findProjects(Api $client, Repositories $repoApi, Repository $api, MessageInterface $response, MessageInterface $singleResponse)
    {
        $client->api('Repositories\Repository')->willReturn($api);
        $client->api('Repositories')->willReturn($repoApi);

        $api->get('foo', 'bar')->willReturn($singleResponse);
        $repoApi->all('foo')->willReturn($response);

        $singleResponse->getContent()->willReturn('{"full_name": "foo/bar"}');
        $response->getContent()->willReturn('[{"full_name": "foo/bar"}]');

        $projects = $this->findProjects('foo/[bar|bazz]+$');

        $projects->shouldBeArray();
        $projects['foo/bar']->shouldHaveType('Rs\Issues\Project');
        $projects['foo/bar']->shouldHaveType('Rs\Issues\Bitbucket\BitbucketProject');
    }
}
