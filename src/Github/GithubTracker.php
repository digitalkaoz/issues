<?php

namespace Rs\Issues\Github;

use Github\Client;
use Github\HttpClient\CachedHttpClient;
use Rs\Issues\Tracker;
use Rs\Issues\Tracker\SearchableTracker;

/**
 * GithubTracker.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class GithubTracker extends SearchableTracker implements Tracker
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

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function getProject($name)
    {
        return $this->requestProject($name, function ($name) {
            list($username, $repo) = explode('/', $name);

            return $this->client->repos()->show($username, $repo);
        }, function ($data) {
            return new GithubProject($data, $this->client, $this->badgeFactory);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function findProjects($name)
    {
        return $this->requestProjects($name, function ($name) {
            list($user) = explode('/', $name);

            return $this->client->user()->repositories($user);
        }, 'full_name');
    }
}
