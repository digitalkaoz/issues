<?php

namespace Rs\Issues\Git;

use Rs\Issues\BadgeFactory;
use Rs\Issues\Exception\NotFoundException;
use Rs\Issues\Project;
use Rs\Issues\RepositoryParser;

/**
 * GitTracker
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
abstract class GitTracker
{
    /**
     * @var BadgeFactory
     */
    protected $badgeFactory;
    /**
     * @var RepositoryParser
     */
    protected $repoParser;

    public function __construct()
    {
        $this->badgeFactory = new BadgeFactory();
        $this->repoParser = new RepositoryParser();
    }

    /**
     * @inheritdoc
     */
    public function setRepositoryParser(RepositoryParser $parser)
    {
        $this->repoParser = $parser;
    }

    /**
     * @inheritdoc
     */
    public function setBadgeFactory(BadgeFactory $factory)
    {
        $this->badgeFactory = $factory;
    }

    /**
     * @param  string            $name
     * @param  \Closure          $finder
     * @param  \Closure          $creator
     * @return Project
     * @throws NotFoundException
     */
    protected function requestProject($name, \Closure $finder, \Closure $creator)
    {
        if (false === $this->repoParser->isConcrete($name)) {
            throw new \InvalidArgumentException(sprintf('no concrete repository name "%s"', $name));
        }

        try {
            return $creator($finder($name));
        } catch (\Exception $e) {
            throw new NotFoundException(sprintf('unable to find "%s"', $name), 0, $e);
        }
    }

    /**
     * searches for projects
     * @param  string    $name
     * @param  \Closure  $finder
     * @param  string    $nameKey
     * @return Project[]
     */
    protected function requestProjects($name, \Closure $finder, $nameKey)
    {
        $projects = [];

        if ($this->repoParser->isConcrete($name)) {
            $project = $this->getProject($name);
            $projects[$project->getName()] = $project;
        } else {
            $repos = $finder($name);

            if (true === $this->repoParser->isWildcard($name)) {
                foreach ((array) $repos as $repo) {
                    $project = $this->getProject($repo[$nameKey]);
                    $projects[$project->getName()] = $project;
                }
            } else {
                foreach ((array) $repos as $repo) {
                    if (false === $this->repoParser->matchesRegex($name, $repo[$nameKey])) {
                        continue;
                    }
                    $project = $this->getProject($repo[$nameKey]);
                    $projects[$project->getName()] = $project;
                }

            }
        }

        return $projects;
    }
}
