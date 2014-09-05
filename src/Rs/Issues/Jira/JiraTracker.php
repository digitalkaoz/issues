<?php
/**
 * issues
 */

namespace Rs\Issues\Jira;

use chobie\Jira\Api;
use chobie\Jira\Api\Authentication\Basic;
use Rs\Issues\Tracker;


/**
 * JiraTracker
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class JiraTracker implements Tracker
{
    /**
     * @var Api
     */
    private $client;

    /**
     * @param Api $client
     */
    public function __construct(Api $client = null)
    {
        $this->client = $client;
    }

    /**
     * @inheritdoc
     */
    public function connect($username = null, $password = null, $host = null)
    {
        $this->client = $this->client ?: new Api($host, new Basic($username, $password));

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getProject($name)
    {
        $p = $this->client->getProject($name);

        return new JiraProject($p, $this->client);
    }
}