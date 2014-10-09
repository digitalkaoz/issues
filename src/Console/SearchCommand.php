<?php

namespace Rs\Issues\Console;

use Rs\Issues\Github\GithubTracker;
use Rs\Issues\Gitlab\GitlabTracker;
use Rs\Issues\Issue;
use Rs\Issues\Jira\JiraTracker;
use Rs\Issues\Project;
use Rs\Issues\Tracker;
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
                new InputArgument('type', InputArgument::REQUIRED, 'the tracker type github|jira|gitlab'),
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
        $tracker = $this->createTracker($input);

        $projects = $tracker->findProjects($input->getArgument('project'));

        $this->listIssues($projects, $output);
    }

    /**
     * @param Project[]       $projects
     * @param OutputInterface $output
     */
    private function listIssues(array $projects, OutputInterface $output)
    {
        $helper = new Table($output);
        $helper->setHeaders(array('project', 'type', 'created at', 'title', 'url'));

        foreach ($projects as $name => $project) {
            foreach ($project->getIssues() as $issue) {
                $helper->addRow(array(
                    $project->getName(),
                    $issue->getType(),
                    $issue->getCreatedAt()->format('Y-m-d H:i'),
                    $issue->getTitle(),
                    $issue->getUrl()
                ));
            }
        }

        $helper->render();
    }

    /**
     * creates the correct tracker
     *
     * @param  InputInterface $input
     * @return Tracker
     */
    private function createTracker(InputInterface $input)
    {
        switch ($input->getArgument('type')) {
            case 'github' :
                $tracker = new GithubTracker($input->getOption('username'));
                break;
            case 'jira' :
                $tracker = new JiraTracker($input->getOption('host'), $input->getOption('username'), $input->getOption('password'));
                break;
            case 'gitlab' :
                $tracker = new GitlabTracker($input->getOption('host'), $input->getOption('username'));
                break;
            default :
                throw new \InvalidArgumentException(sprintf('unknown type "%s"', $input->getArgument('type')));
        }

        return $tracker;
    }
}
