<?php

namespace spec\Rs\Issues\Gitlab;

use Gitlab\Api\Issues;
use Gitlab\Api\MergeRequests;
use Gitlab\Api\Repositories;
use Gitlab\Client;
use PhpSpec\ObjectBehavior;
use Rs\Issues\BadgeFactory;

class GitlabProjectSpec extends ObjectBehavior
{
    private $data = array(
        'path_with_namespace' => 'foo/bar',
        'description'         => 'lorem ipsum',
        'web_url'             => 'http://foo.com'
    );

    public function let(Client $client)
    {
        $this->beConstructedWith($this->data, $client, new BadgeFactory());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Gitlab\GitlabProject');
        $this->shouldHaveType('Rs\Issues\Project');
    }

    public function it_returns_the_type()
    {
        $this->getType()->shouldReturn('gitlab');
    }

    public function it_returns_the_fullname()
    {
        $this->getName()->shouldReturn('foo/bar');
    }

    public function it_returns_the_description()
    {
        $this->getDescription()->shouldReturn('lorem ipsum');
    }

    public function it_returns_the_url()
    {
        $this->getUrl()->shouldReturn('http://foo.com');
    }

    public function it_returns_the_badges(Client $client, Repositories $api)
    {
        $client->api('repositories')->willReturn($api);

        $api->getFile('foo/bar', 'composer.json', 'master')->shouldBeCalled()->willReturn(array('encoding' => 'base64', 'content' => base64_encode('{ "name" : "foo/bar"}')));

        $this->getBadges()->shouldBeArray();
        $this->getBadges()->shouldHaveCount(2);
    }

    public function it_returns_its_issues(Client $client, Issues $issuesApi, MergeRequests $mergesApi)
    {
        $client->api('issues')->willReturn($issuesApi);
        $client->api('merge_requests')->willReturn($mergesApi);

        $issuesApi->all('foo/bar', 1, 9999, array('state'=>'open'))->shouldBeCalled()->willReturn(array(array('state'=>'opened'), array('state'=>'closed')));
        $mergesApi->opened('foo/bar', 1, 9999)->shouldBeCalled()->willReturn(array(array()));

        $result = $this->getIssues();

        $result->shouldHaveCount(2);

        $result[0]->shouldHaveType('Rs\Issues\Gitlab\GitlabIssue');
        $result[0]->getType()->shouldBe('issue');

        $result[1]->shouldHaveType('Rs\Issues\Gitlab\GitlabIssue');
        $result[1]->getType()->shouldBe('merge');

    }
}
