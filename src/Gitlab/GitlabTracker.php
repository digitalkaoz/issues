<?php

namespace Rs\Issues\Gitlab;

use Gitlab\Api\Projects;
use Gitlab\Client;
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
     * @param string $host
     * @param string $token
     * @param Client $client
     */
    public function __construct($host, $token = null, Client $client = null)
    {
        $this->client = $client ?: new Client($host);

        if ($token) {
            $this->client->authenticate($token, Client::AUTH_URL_TOKEN);
        }
    }

    /**
     * @inheritdoc
     */
    public function getProject($name)
    {
        $api = $this->client->api('projects');
        /** @var Projects $api */
        $data = $api->show($name);

        return new GitlabProject((array) $data, $this->client);
    }
}
