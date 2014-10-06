<?php

namespace spec\Rs\Issues\Github;

use Github\Api\Issue;
use Github\Api\Repo;
use Github\Api\Repository\Contents;
use Github\Client;
use Github\HttpClient\HttpClient;
use Guzzle\Http\Message\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Rs\Issues\BadgeFactory;

class GithubProjectSpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $this->beConstructedWith(array(
            'full_name'   => 'foo/bar',
            'description' => 'lorem ipsum',
            'html_url'    => 'http://foo.com',
            'name'        => 'bar',
            'owner'       => array('login' => 'foo')
        ), $client, new BadgeFactory());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Github\GithubProject');
        $this->shouldHaveType('Rs\Issues\Project');
    }

    public function it_returns_the_fullname()
    {
        $this->getName()->shouldReturn('foo/bar');
    }

    public function it_returns_the_type()
    {
        $this->getType()->shouldReturn('github');
    }

    public function it_returns_the_description()
    {
        $this->getDescription()->shouldReturn('lorem ipsum');
    }

    public function it_returns_the_url()
    {
        $this->getUrl()->shouldReturn('http://foo.com');
    }

    public function it_returns_Issue_objects_on_getIssues(Client $client, Issue $api, HttpClient $http, Response $response)
    {
        $client->issue()->willReturn($api);
        $client->getHttpClient()->willReturn($http);

        $api->getPerPage()->willReturn(5);
        $api->setPerPage(Argument::any())->willReturn();

        $http->getLastResponse()->willReturn($response);

        $response->getHeader('Link')->willReturn(null);

        $api->all('foo', 'bar', array('state' => 'open'))->willReturn(array(
            array(
                'number' => 1,
            ),
            array(
                'number' => 5,
            ),
        ));

        $result = $this->getIssues();

        $result->shouldBeArray();
        $result->shouldHaveCount(2);

        $result[0]->shouldHaveType('Rs\Issues\Github\GithubIssue');
        $result[0]->getId()->shouldBe(1);

        $result[1]->shouldHaveType('Rs\Issues\Github\GithubIssue');
        $result[1]->getId()->shouldBe(5);
    }

    public function it_returns_the_badges(Client $client, Repo $api, Contents $content)
    {
        $client->repos()->willReturn($api);
        $api->contents()->willReturn($content);

        $content->show('foo', 'bar', '.travis.yml')->shouldBeCalled()->willReturn(array('encoding' => 'base64', 'content' => base64_encode('{}')));
        $content->show('foo', 'bar', 'composer.json')->shouldBeCalled()->willReturn(array('encoding' => 'base64', 'content' => base64_encode('{ "name" : "foo/bar"}')));

        $this->getBadges()->shouldBeArray();
        $this->getBadges()->shouldHaveCount(3);
    }
}
