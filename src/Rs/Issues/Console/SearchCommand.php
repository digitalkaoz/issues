<?php

namespace Rs\Issues\Console;

use Rs\Issues\Github\GithubTracker;
use Rs\Issues\Issue;
use Rs\Issues\Jira\JiraTracker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * SearchCommand
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class SearchCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('search')
            ->setDefinition(new InputDefinition(array(
                new InputOption('username', 'u', InputOption::VALUE_REQUIRED, 'the username/token to use for authentication'),
                new InputOption('password', 'p', InputOption::VALUE_REQUIRED, 'the password to use for authentication'),
                new InputOption('host', 'd', InputOption::VALUE_REQUIRED, 'the host to connect'),
                new InputArgument('type', InputArgument::REQUIRED, 'the tracker type github|jira'),
                new InputArgument('project', InputArgument::REQUIRED, 'the project name'),
            )))
            ->setDescription('search for issues in a tracker and project')
            ->setHelp(<<<EOT
The <info>search</info>
EOT
            );
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        switch ($input->getArgument('type')) {
            case 'github' :
                $tracker = new GithubTracker();
                break;
            case 'jira' :
                $tracker = new JiraTracker();
                break;
            default :
                throw new \InvalidArgumentException('unknown type, choose github|jira');
        }

        $tracker->connect($input->getOption('username'), $input->getOption('password'), $input->getOption('host'));
        $project = $tracker->getProject($input->getArgument('project'));
        $issues = $project->getIssues();

        $this->listIssues($issues, $output);
    }

    /**
     * @param array           $issues
     * @param OutputInterface $output
     */
    private function listIssues(array $issues, OutputInterface $output)
    {
        $helper = $this->getHelper('table');
        /** @var Table $helper */

        $helper->setHeaders(array('state', 'created at', 'title', 'url'));

        foreach ($issues as $issue) {
            /** @var Issue $issue */
            $helper->addRow(array(
                $issue->getState(),
                $issue->getCreatedAt()->format('Y-m-d H:i'),
                $issue->getTitle(),
                $issue->getUrl()
            ));
        }

        $helper->render($output);
    }
}
