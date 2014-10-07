<?php
/**
 * issues
 */

namespace Rs\Issues\Github;

use Rs\Issues\Issue;

/**
 * GithubIssue
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class GithubIssue implements Issue
{
    private $raw = array();

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->raw = $data;
    }

    /**
     * @inheritdoc
     */
    public function getUrl()
    {
        return $this->raw['html_url'];
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->raw['title'];
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return $this->raw['body'];
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt()
    {
        return new \DateTime($this->raw['created_at']);
    }

    /**
     * @inheritdoc
     */
    public function getClosedAt()
    {
        return $this->raw['closed_at'] ? new \DateTime($this->raw['closed_at']) : null;
    }

    /**
     * @inheritdoc
     */
    public function getState()
    {
        return $this->raw['state']; //TODO use own consts?!
    }

    /**
     * @inheritdoc
     */
    public function getCommentCount()
    {
        return $this->raw['comments'];
    }

    /**
     * @inheritdoc
     */
    public function getUpdatedAt()
    {
        return $this->raw['updated_at'] ? new \DateTime($this->raw['updated_at']) : null;
    }

    /**
     * @inheritdoc
     */
    public function getAssignee()
    {
        return isset($this->raw['assignee']['login']) ? $this->raw['assignee']['login'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getAssigneeUrl()
    {
        if ($this->getAssignee()) {
            return $this->raw['assignee']['html_url'];
        }
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->raw['number'];
    }

    /**
     * @inheritdoc
     */
    public function getAuthor()
    {
        return $this->raw['user']['login'];
    }

    /**
     * @inheritdoc
     */
    public function getAuthorUrl()
    {
        return $this->raw['user']['html_url'];
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return isset($this->raw['pull_request']) ? 'pull' : 'issue';
    }

    /**
     * @inheritdoc
     */
    public function getTags()
    {
        $labels = isset($this->raw['labels']) ? $this->raw['labels'] : array();
        $return = array();

        foreach ($labels as $label) {
            $return[] = $label['name'];
        }

        return $return;
    }
}
