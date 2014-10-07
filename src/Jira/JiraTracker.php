<?php
namespace Rs\Issues\Jira;

use Jira_Api as Api; //chobie\Jira\Api;
use Jira_Api_Authentication_Basic as Basic; // chobie\Jira\Api\Authentication\Basic;
use \Jira_Api_Authentication_Anonymous as Anonymous; // chobie\Jira\Api\Authentication\Anonymous
use Rs\Issues\BadgeFactory;
use Rs\Issues\Tracker;

/**
 * JiraTracker
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class JiraTracker implements Tracker
{
    /**
     * @var Api
     */
    private $client;
    /**
     * @var BadgeFactory
     */
    private $badgeFactory;

    /**
     * @param string       $host
     * @param string       $username
     * @param string       $password
     * @param Api          $client
     * @param BadgeFactory $badgeFactory
     */
    public function __construct($host, $username = null, $password = null, Api $client = null, BadgeFactory $badgeFactory = null)
    {
        $auth = $username && $password ? new Basic($username, $password) : new Anonymous();

        $this->client = $client ?: new Api($host, $auth);
        $this->badgeFactory = $badgeFactory ?: new BadgeFactory();
    }

    /**
     * @inheritdoc
     */
    public function getProject($name)
    {
        $p = $this->client->getProject($name);

        if (!is_array($p) || !isset($p['key'])) {
            throw new \RuntimeException('invalid Project');
        }

        return new JiraProject($p, $this->client, $this->badgeFactory);
    }
}
