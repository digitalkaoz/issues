<?php

namespace Rs\Issues\Gitlab;

use Gitlab\Client;
use Rs\Issues\Tracker;

/**
 * GitlabTracker
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class GitlabTracker implements Tracker
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
        $this->client = $client ?: new Client(null);
    }

    /**
     * @inheritdoc
     */
    public function connect($username = null, $password = null, $host = null)
    {
        $this->client->setBaseUrl($host);
        $prop = new \ReflectionProperty(get_class($this->client->getHttpClient()), 'base_url');
        $prop->setAccessible(true);

        $prop->setValue($this->client->getHttpClient(), $host);

        if ($username) {
            $this->client->authenticate($username, Client::AUTH_URL_TOKEN);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getProject($name)
    {
        $data = $this->client->api('projects')->show($name);

        return new GitlabProject($data, $this->client);
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
}
