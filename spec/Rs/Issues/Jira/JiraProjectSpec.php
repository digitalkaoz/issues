<?php

namespace spec\Rs\Issues\Jira;

use chobie\Jira\Api;
use PhpSpec\ObjectBehavior;

class JiraProjectSpec extends ObjectBehavior
{
    public function let(Api $client)
    {
        $this->beConstructedWith(array(
            'name'        => 'foo',
            'description' => 'bar',
            'key'         => 'FOOBAR'
        ), $client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Jira\JiraProject');
        $this->shouldHaveType('Rs\Issues\Project');
    }

    public function it_returns_its_name()
    {
        $this->getName()->shouldBe('foo');
    }

    public function it_returns_its_description()
    {
        $this->getDescription()->shouldBe('bar');
    }

//    function it_returns_its_url()
//    {
//        //$this->getUrl()->shouldBe('http://jira.google.com');
//    }

    public function it_returns_its_issues(Api $client, Api\Result $result)
    {
        $client->search("project = FOOBAR AND status != closed AND status != resolved", 0, 50, null)->shouldBeCalled()->willReturn($result);
        $result = $this->getIssues();

        $result->shouldBeArray();
    }
}
