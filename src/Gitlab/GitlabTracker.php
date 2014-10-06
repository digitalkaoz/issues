<?php

namespace Rs\Issues\Gitlab;

use Gitlab\Api\Projects;
use Gitlab\Client;
use Rs\Issues\BadgeFactory;
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
     * @param string       $host
     * @param string       $token
     * @param Client       $client
     * @param BadgeFactory $badgeFactory
     */
    public function __construct($host, $token = null, Client $client = null, BadgeFactory $badgeFactory = null)
    {
        $this->client = $client ?: new Client($host);

        if ($token) {
            $this->client->authenticate($token, Client::AUTH_URL_TOKEN);
        }

        $this->badgeFactory = $badgeFactory ?: new BadgeFactory();
    }

    /**
     * @inheritdoc
     */
    public function getProject($name)
    {
        $api = $this->client->api('projects');
        /** @var Projects $api */
        $data = $api->show($name);

        return new GitlabProject((array) $data, $this->client, $this->badgeFactory);
    }
}
