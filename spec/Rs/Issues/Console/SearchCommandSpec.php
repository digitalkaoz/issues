<?php

namespace spec\Rs\Issues\Console;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class SearchCommandSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\Console\SearchCommand');
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }

    public function it_displays_a_search_result_table_for_github()
    {
        $input = new ArrayInput(array('type' => 'github', 'project' => 'digitalkaoz/issues'));
        $output = new BufferedOutput();

        $this->run($input, $output)->shouldPrintATable($output);
    }

    public function it_displays_a_search_result_table_for_gitlab()
    {
        $input = new ArrayInput(array('type' => 'gitlab', 'project' => 'gitlab/gitlab-shell', '-d'=>'http://demo.gitlab.com/api/v3/', '-u'=>'CPcomi7q3qyREs8wkpQz'));
        $output = new BufferedOutput();

        $this->run($input, $output)->shouldPrintATable($output);
    }

    public function it_displays_a_search_result_table_for_jira()
    {
        $input = new ArrayInput(array('type' => 'jira', 'project' => 'CEP', '-d'=>'https://jira.atlassian.com'));
        $output = new BufferedOutput();

        $this->run($input, $output)->shouldPrintATable($output);
    }

    public function it_needs_an_implemented_tracker()
    {
        $input = new ArrayInput(array('type' => 'foo', 'project' => 'bar'));
        $output = new BufferedOutput();

        $this->shouldThrow('\InvalidArgumentException')->during('run', array($input, $output));
    }

    public function getMatchers()
    {
        return array(
            'printATable' => function ($s, BufferedOutput $output) {
                return 1 === preg_match_all('/(.)*\|( )+type( )+\|( )+created at( )+\|( )+title( )+\|( )+url( )+\|(.)*/', $output->fetch());
            }
        );
    }
}
