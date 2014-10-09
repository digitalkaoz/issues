<?php

namespace Rs\Issues\Gitlab;

use Gitlab\Api\Projects;
use Gitlab\Client;
use Rs\Issues\BadgeFactory;
use Rs\Issues\Project;
use Rs\Issues\RepositoryParser;
use Rs\Issues\Tracker;

/**
 * GitlabTracker
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class GitlabTracker implements Tracker
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var BadgeFactory
     */
    private $badgeFactory;
    /**
     * @var RepositoryParser
     */
    private $repoParser;

    /**
     * @param string           $host
     * @param string           $token
     * @param Client           $client
     * @param BadgeFactory     $badgeFactory
     * @param RepositoryParser $repoParser
     */
    public function __construct($host, $token = null, Client $client = null, BadgeFactory $badgeFactory = null, RepositoryParser $repoParser = null)
    {
        $this->client = $client ?: new Client($host);

        if ($token) {
            $this->client->authenticate($token, Client::AUTH_URL_TOKEN);
        }

        $this->badgeFactory = $badgeFactory ?: new BadgeFactory();
        $this->repoParser = $repoParser ?: new RepositoryParser();
    }

    /**
     * @inheritdoc
     */
    public function getProject($name)
    {
        if (false === $this->repoParser->isConcrete($name)) {
            throw new \InvalidArgumentException(sprintf('no concrete repository name "%s"', $name));
        }

        $api = $this->client->api('projects');
        /** @var Projects $api */
        $data = $api->show($name);

        return new GitlabProject((array) $data, $this->client, $this->badgeFactory);
    }

    /**
     * @param $name
     * @return Project[]
     */
    public function findProjects($name)
    {
        $projects = array();

        if ($this->repoParser->isConcrete($name)) {
            $project = $this->getProject($name);
            $projects[$project->getName()] = $project;
        } else {
            list($user, ) = explode('/', $name);
            $api = $this->client->api('projects');
            /** @var Projects $api */
            $repos = $api->accessible(1, 9999);

            if (true === $this->repoParser->isWildcard($name)) {
                foreach ($repos as $repo) {
                    $project = $this->getProject($repo['path_with_namespace']);
                    $projects[$project->getName()] = $project;
                }
            } else {
                foreach ($repos as $repo) {
                    if (false === $this->repoParser->matchesRegex($name, $repo['path_with_namespace'])) {
                        continue;
                    }
                    $project = $this->getProject($repo['path_with_namespace']);
                    $projects[$project->getName()] = $project;
                }

            }
        }

        return $projects;
    }
}
