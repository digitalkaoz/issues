<?php

namespace Rs\Issues\Github;

use Github\Client;
use Rs\Issues\Project;


/**
 * GithubProject
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class GithubProject implements Project
{
    private $raw = array();

    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $issues = array();

    /**
     * @param array $data
     * @param Client $client
     */
    public function __construct(array $data, Client $client)
    {
        $this->raw = $data;
        $this->client = $client;
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return $this->raw['description'];
    }

    /**
     * @inheritdoc
     */
    public function getUrl()
    {
        return $this->raw['html_url'];
    }

    /**
     * @inheritdoc
     */
    public function getIssues(array $criteria = array())
    {
        if (!$criteria) {
            $criteria = array('state' => 'open');
        }

        list($username, $repo) = explode('/', $this->getName());

        $issues = $this->client->issue()->all($username, $repo, $criteria);

        $newIssues = array();

        foreach ($issues as $issue) {
            $newIssues[] = new GithubIssue($issue);
        }

        return $this->issues = $newIssues;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->raw['full_name'];
    }

    public function getRaw($key = null)
    {
        if ($key && array_key_exists($key, $this->raw)) {
            return $this->raw[$key];
        }

        return $this->raw;
    }
} 