<?php

namespace spec\Rs\Issues\Jira;

use chobie\Jira\Api;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JiraProjectSpec extends ObjectBehavior
{
    function let(Api $client)
    {
        $this->beConstructedWith(array(
            'name'        => 'foo',
            'description' => 'bar',
            'key'         => 'FOOBAR'
        ), $client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Jira\JiraProject');
        $this->shouldHaveType('Rs\Issues\Project');
    }

    function it_returns_its_name()
    {
        $this->getName()->shouldBe('foo');
    }

    function it_returns_its_description()
    {
        $this->getDescription()->shouldBe('bar');
    }

//    function it_returns_its_url()
//    {
//        //$this->getUrl()->shouldBe('http://jira.google.com');
//    }

    function it_returns_its_issues(Api $client, Api\Result $result)
    {
        $client->search("project = FOOBAR AND status != closed AND status != resolved", 0, 50, null)->shouldBeCalled()->willReturn($result);
        $result = $this->getIssues();

        $result->shouldBeArray();
    }
}
