<?php

namespace spec\Rs\Issues\Github;

use Github\Api\Issue;
use Github\Client;
use Github\HttpClient\HttpClient;
use Guzzle\Http\Message\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GithubProjectSpec extends ObjectBehavior
{
    public function let(Client $client)
    {
        $this->beConstructedWith(array(
            'full_name'   => 'foo/bar',
            'description' => 'lorem ipsum',
            'html_url'    => 'http://foo.com'
        ), $client);
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

    public function it_can_return_the_raw_data_from_github()
    {
        $this->getRaw()->shouldReturn(array(
            'full_name'   => 'foo/bar',
            'description' => 'lorem ipsum',
            'html_url'    => 'http://foo.com'
        ));
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
        $result[0]->getNumber()->shouldBe(1);

        $result[1]->shouldHaveType('Rs\Issues\Github\GithubIssue');
        $result[1]->getNumber()->shouldBe(5);
    }
}
