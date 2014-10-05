<?php
/**
 * issues
 */

namespace Rs\Issues\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Application
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Application extends BaseApplication
{
    public function __construct()
    {
        parent::__construct('issues', '@@version@@');
    }

    /**
     * {@inheritDoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->registerCommands();

        return parent::doRun($input, $output);
    }

    /**
     * Initializes the commands
     */
    private function registerCommands()
    {
        $this->add(new SearchCommand());
    }
}
