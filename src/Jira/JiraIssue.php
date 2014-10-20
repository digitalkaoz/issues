<?php

namespace Rs\Issues\Jira;

use Rs\Issues\Issue;
use Jira_Issue as ApiIssue; //chobie\Jira\Issue as ApiIssue;

/**
 * JiraIssue
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class JiraIssue implements Issue
{
    /**
     * @var ApiIssue
     */
    private $raw;

    /**
     * @param ApiIssue $issue
     */
    public function __construct(ApiIssue $issue)
    {
        $this->raw = $issue;
    }

    /**
     * @inheritdoc
     */
    public function getUrl()
    {
        return $this->raw->getSelf();
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->raw->getSummary();
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return $this->raw->getDescription();
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt()
    {
        return new \DateTime($this->raw->getCreated());
    }

    /**
     * @inheritdoc
     */
    public function getClosedAt()
    {
        return $this->raw->getResolutionDate() ? new \DateTime($this->raw->getResolutionDate()) : null;
    }

    /**
     * @inheritdoc
     */
    public function getState()
    {
        return \igorw\get_in($this->raw->getStatus(), ['name']);
    }

    /**
     * @inheritdoc
     */
    public function getCommentCount()
    {
        return \igorw\get_in($this->raw->get('comment'), ['total']);
    }

    /**
     * @inheritdoc
     */
    public function getUpdatedAt()
    {
        return $this->raw->getUpdated() ? new \DateTime($this->raw->getUpdated()) : null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->raw->getKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthor()
    {
        return \igorw\get_in($this->raw->getReporter(), ['displayName']);
    }

    /**
     * @inheritdoc
     */
    public function getAuthorUrl()
    {
        return $this->getUserUrl($this->getAuthor());
    }

    /**
     * @inheritdoc
     */
    public function getAssignee()
    {
        if ($assignee = $this->raw->getAssignee()) {
            return \igorw\get_in($assignee, ['displayName']);
        }
    }

    /**
     * @inheritdoc
     */
    public function getAssigneeUrl()
    {
        if ($this->getAssignee()) {
            return $this->getUserUrl($this->getAssignee());
        }
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return \igorw\get_in($this->raw->getIssueType(), ['name']);
    }

    /**
     * @inheritdoc
     */
    public function getTags()
    {
        return $this->raw->getLabels();
    }

    /**
     * @param  string $username
     * @return string
     */
    private function getUserUrl($username)
    {
        $base = parse_url($this->raw->getSelf(), PHP_URL_HOST);
        $proto = parse_url($this->raw->getSelf(), PHP_URL_SCHEME);

        return sprintf('%s://%s/ViewProfile.jspa?name=%s', $proto, $base, $username);
    }
}
