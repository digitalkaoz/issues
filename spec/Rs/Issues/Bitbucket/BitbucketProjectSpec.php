<?php

namespace spec\Rs\Issues\Bitbucket;

use Bitbucket\API\Api;
use Bitbucket\API\Repositories\Issues;
use Bitbucket\API\Repositories\PullRequests;
use Bitbucket\API\Repositories\Src;
use Buzz\Message\MessageInterface;
use Buzz\Message\Response;
use PhpSpec\ObjectBehavior;
use Rs\Issues\Utils\BadgeFactory;

class BitbucketProjectSpec extends ObjectBehavior
{
    public function let(Api $client)
    {
        $this->beConstructedWith([
            'full_name'   => 'foo/bar',
            'description' => 'lorem ipsum',
            'links'       => ['self'     => ['href' => 'http://foo.com']],
            'owner'       => ['username' => 'foo'],
            'name'        => 'bar',
        ], $client, new BadgeFactory());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Bitbucket\BitbucketProject');
        $this->shouldHaveType('Rs\Issues\Project');
    }

    public function it_returns_the_fullname()
    {
        $this->getName()->shouldReturn('foo/bar');
    }

    public function it_returns_the_type()
    {
        $this->getType()->shouldReturn('bitbucket');
    }

    public function it_returns_the_description()
    {
        $this->getDescription()->shouldReturn('lorem ipsum');
    }

    public function it_returns_the_url()
    {
        $this->getUrl()->shouldReturn('http://foo.com');
    }

    public function it_returns_the_badges(Api $client, Src $api, Response $response)
    {
        $client->api('Repositories\Src')->willReturn($api);

        $api->raw('foo', 'bar', 'master', 'composer.json')->shouldBeCalled()->willReturn($response);

        $response->isSuccessful()->willReturn(true);
        $response->getContent()->shouldBeCalled()->willReturn('{ "name" : "foo/bar"}');

        $this->getBadges()->shouldBeArray();
        $this->getBadges()->shouldHaveCount(2);
    }

    public function it_returns_its_issues(Api $client, Issues $issuesApi, PullRequests $mergesApi, MessageInterface $response)
    {
        $client->api('Repositories\Issues')->willReturn($issuesApi);
        $client->api('Repositories\PullRequests')->willReturn($mergesApi);

        $response->getContent()->shouldBeCalled()->willReturn('{ "issues":[{"status" : "open"},{ "status" : "closed"}], "values":[{"status" : "open"},{ "status" : "closed"}] }');

        $issuesApi->all('foo', 'bar', ['state' => 'OPEN'])->shouldBeCalled()->willReturn($response);
        $mergesApi->all('foo', 'bar', ['state' => 'OPEN'])->shouldBeCalled()->willReturn($response);

        $result = $this->getIssues();

        $result->shouldHaveCount(2);

        $result[0]->shouldHaveType('Rs\Issues\Bitbucket\BitbucketIssue');
        $result[0]->getType()->shouldBe('issue');

        $result[1]->shouldHaveType('Rs\Issues\Bitbucket\BitbucketIssue');
        $result[1]->getType()->shouldBe('pull');
    }
}
