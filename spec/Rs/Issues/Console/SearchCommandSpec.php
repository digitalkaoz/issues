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

    public function it_displays_a_search_result_table()
    {
        $input = new ArrayInput(array('type' => 'github', 'project' => 'digitalkaoz/issues'));
        $output = new BufferedOutput();

        $this->run($input, $output)->shouldPrintATable($output);
    }

    public function getMatchers()
    {
        return array(
            'printATable' => function ($s, BufferedOutput $output) {
                return false !== strpos($output->fetch(), <<<EOS
+-------+------------------+------------------------------------------+------------------------------------------------+
| type  | created at       | title                                    | url                                            |
+-------+------------------+------------------------------------------+------------------------------------------------+
EOS
                    );
            }
        );
    }
}
