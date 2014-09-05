<?php

namespace Rs\Issues\Github;

use Github\Client;
use Rs\Issues\Tracker;

/**
 * GithubTracker
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class GithubTracker implements Tracker
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new \Github\Client(new \Github\HttpClient\CachedHttpClient());
    }

    /**
     * @inheritdoc
     */
    public function connect($username = null, $password = null, $host = null)
    {
        if ($username) {
            $this->client->authenticate($username, $password, Client::AUTH_HTTP_PASSWORD);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getProject($name)
    {
        list($username, $repo) = explode('/', $name);

        $data = $this->client->repos()->show($username, $repo);

        return new GithubProject($data, $this->client);
    }
}
