<?php
/**
 * issues
 */

namespace Rs\Issues\Jira;

use chobie\Jira\Api;
use chobie\Jira\Issues\Walker;
use Rs\Issues\BadgeFactory;
use Rs\Issues\Project;

/**
 * JiraProject
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class JiraProject implements Project
{
    /**
     * @var array
     */
    private $raw;
    /**
     * @var Api
     */
    private $client;
    /**
     * @var BadgeFactory
     */
    private $badgeFactory;

    /**
     * @param array        $data
     * @param Api          $client
     * @param BadgeFactory $badgeFactory
     */
    public function __construct(array $data, Api $client, BadgeFactory $badgeFactory)
    {
        $this->raw = $data;
        $this->client = $client;
        $this->badgeFactory = $badgeFactory;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->raw['name'];
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->raw['description'];
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        // TODO: Implement getUrl() method.
    }

    /**
     * @inheritdoc
     */
    public function getIssues(array $criteria = array('status != closed', 'status != resolved'))
    {
        $conditions = join(' AND ', $criteria);
        $walker = new Walker($this->client);
        $walker->push(sprintf('project = %s AND %s', $this->raw['key'], $conditions));

        $issues = array();
        foreach ($walker as $k => $issue) {
            $issues[] = new JiraIssue($issue);
        }

        return $issues;
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return 'jira';
    }

    /**
     * @inheritdoc
     */
    public function getBadges()
    {
        return array();
    }
}
