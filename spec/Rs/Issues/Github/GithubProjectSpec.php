<?php

namespace spec\Rs\Issues\Github;

use Github\Api\Issue;
use Github\Client;
use Github\HttpClient\HttpClient;
use Github\HttpClient\HttpClientInterface;
use Guzzle\Http\Message\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GithubProjectSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith(array(
            'full_name'   => 'foo/bar',
            'description' => 'lorem ipsum',
            'html_url'    => 'http://foo.com'
        ), $client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Github\GithubProject');
        $this->shouldHaveType('Rs\Issues\Project');
    }

    function it_returns_the_fullname()
    {
        $this->getName()->shouldReturn('foo/bar');
    }

    function it_returns_the_description()
    {
        $this->getDescription()->shouldReturn('lorem ipsum');
    }

    function it_returns_the_url()
    {
        $this->getUrl()->shouldReturn('http://foo.com');
    }

    function it_can_return_the_raw_data_from_github()
    {
        $this->getRaw()->shouldReturn(array(
            'full_name'   => 'foo/bar',
            'description' => 'lorem ipsum',
            'html_url'    => 'http://foo.com'
        ));
    }

    function it_returns_Issue_objects_on_getIssues(Client $client, Issue $api, HttpClient $http, Response $response)
    {
        $client->issue()->willReturn($api);
        $client->getHttpClient()->shouldBeCalled()->willReturn($http);

        $http->getLastResponse()->shouldBeCalled()->willReturn($response);

        $result = $this->getIssues();

        $result->shouldBeArray();
    }
}
