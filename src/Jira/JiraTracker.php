<?php
namespace Rs\Issues\Jira;

use Jira_Api as Api; //chobie\Jira\Api;
use Jira_Api_Authentication_Basic as Basic; // chobie\Jira\Api\Authentication\Basic;
use \Jira_Api_Authentication_Anonymous as Anonymous; // chobie\Jira\Api\Authentication\Anonymous

use Rs\Issues\Utils\BadgeFactory;
use Rs\Issues\Exception\NotFoundException;
use Rs\Issues\Utils\RepositoryParser;
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
     * @param string $host
     * @param string $username
     * @param string $password
     * @param Api    $client
     */
    public function __construct($host, $username = null, $password = null, Api $client = null)
    {
        $auth = $username && $password ? new Basic($username, $password) : new Anonymous();

        $this->client = $client ?: new Api($host, $auth);
        $this->badgeFactory = new BadgeFactory();
    }

    /**
     * @inheritdoc
     */
    public function getProject($name)
    {
        $project = $this->client->getProject($name);

        if (!is_array($project) || !isset($project['key'])) {
            throw new NotFoundException(sprintf('unable to find "%s"', $name));
        }

        return new JiraProject($project, $this->client, $this->badgeFactory);
    }

    /**
     * @inheritdoc
     */
    public function findProjects($name)
    {
        $project = $this->getProject($name);

        return array($project->getName() => $project);
    }

    /**
     * @inheritdoc
     */
    public function setRepositoryParser(RepositoryParser $parser)
    {
        throw new \BadMethodCallException('jira doesnt support searching for projects');
    }

    /**
     * @inheritdoc
     */
    public function setBadgeFactory(BadgeFactory $factory)
    {
        $this->badgeFactory = $factory;
    }
}
