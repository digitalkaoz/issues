<?php

namespace Rs\Issues\Gitlab;

use Gitlab\Api\Projects;
use Gitlab\Client;
use Rs\Issues\BadgeFactory;
use Rs\Issues\Git\GitTracker;
use Rs\Issues\Tracker;

/**
 * GitlabTracker
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class GitlabTracker extends GitTracker implements Tracker
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

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    public function getProject($name)
    {
        return $this->requestProject($name, function ($name) {
            $api = $this->client->api('projects');
            /** @var Projects $api */

            return $api->show($name);
        }, function ($data) {
            return new GitlabProject($data, $this->client, $this->badgeFactory);
        });
    }

    /**
     * @inheritdoc
     */
    public function findProjects($name)
    {
        return $this->requestProjects($name, function ($name) {
            $api = $this->client->api('projects');
            /** @var Projects $api */

            return $api->accessible(1, 9999);
        }, 'path_with_namespace');
    }

}
