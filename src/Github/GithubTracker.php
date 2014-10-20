<?php

namespace Rs\Issues\Github;

use Github\Client;
use Github\HttpClient\CachedHttpClient;
use Rs\Issues\BadgeFactory;
use Rs\Issues\RepositoryParser;
use Rs\Issues\Tracker;

/**
 * GithubTracker
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class GithubTracker implements Tracker
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
     * @param string $token
     * @param Client $client
     */
    public function __construct($token = null, Client $client = null)
    {
        $this->client = $client ?: new Client(new CachedHttpClient());

        if ($token) {
            $this->client->authenticate($token, null, Client::AUTH_HTTP_PASSWORD);
        }

        $this->badgeFactory = new BadgeFactory();
        $this->repoParser = new RepositoryParser();
    }

    /**
     * @inheritdoc
     */
    public function getProject($name)
    {
        if (false === $this->repoParser->isConcrete($name)) {
            throw new \InvalidArgumentException(sprintf('no concrete repository name "%s"', $name));
        }

        list($username, $repo) = explode('/', $name);

        $data = $this->client->repos()->show($username, $repo);

        return new GithubProject($data, $this->client, $this->badgeFactory);
    }

    /**
     * @inheritdoc
     */
    public function findProjects($name)
    {
        $projects = array();

        if ($this->repoParser->isConcrete($name)) {
            $project = $this->getProject($name);
            $projects[$project->getName()] = $project;
        } else {
            list($user, ) = explode('/', $name);
            $repos = $this->client->user()->repositories($user);

            if (true === $this->repoParser->isWildcard($name)) {
                foreach ($repos as $repo) {
                    $project = $this->getProject($repo['full_name']);
                    $projects[$project->getName()] = $project;
                }
            } else {
                foreach ($repos as $repo) {
                    if (false === $this->repoParser->matchesRegex($name, $repo['full_name'])) {
                        continue;
                    }
                    $project = $this->getProject($repo['full_name']);
                    $projects[$project->getName()] = $project;
                }

            }
        }

        return $projects;
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
}
