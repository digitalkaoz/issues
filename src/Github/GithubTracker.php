<?php

namespace Rs\Issues\Github;

use Github\Client;
use Github\HttpClient\CachedHttpClient;
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
     * @param string $token
     * @param Client $client
     */
    public function __construct($token = null, Client $client = null)
    {
        $this->client = $client ?: new Client(new CachedHttpClient());

        if ($token) {
            $this->client->authenticate($token, null, Client::AUTH_HTTP_PASSWORD);
        }
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
