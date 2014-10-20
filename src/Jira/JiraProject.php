<?php

namespace Rs\Issues\Jira;

use Jira_Api as Api; //chobie\Jira\Api;
use Jira_Issues_Walker as Walker; //chobie\Jira\Issues\Walker;
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
    private $raw = [];
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
     * @inheritdoc
     */
    public function getName()
    {
        return \igorw\get_in($this->raw, ['name']);
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return \igorw\get_in($this->raw, ['description']);
    }

    /**
     * @inheritdoc
     */
    public function getUrl()
    {
        $base = parse_url(\igorw\get_in($this->raw, ['self']), PHP_URL_HOST);
        $proto = parse_url(\igorw\get_in($this->raw, ['self']), PHP_URL_SCHEME);

        return sprintf('%s://%s/browse/%s', $proto, $base, $this->raw['key']);
    }

    /**
     * @inheritdoc
     */
    public function getIssues(array $criteria = ['status != closed', 'status != resolved'])
    {
        $conditions = join(' AND ', $criteria);

        $walker = new Walker($this->client);
        $walker->push(sprintf('project = %s AND %s', $this->raw['key'], $conditions));

        $issues = [];
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
        return [];
    }
}
