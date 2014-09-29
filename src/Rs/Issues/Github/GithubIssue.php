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
    public function getText()
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

    public function getAssignee()
    {
        return $this->raw['assignee']['login'];
    }

    public function getAssigneeUrl()
    {
        return $this->raw['assignee']['html_url'];
    }

    public function getNumber()
    {
        return $this->raw['number'];
    }

    public function getOwner()
    {
        return $this->raw['user']['login'];
    }

    public function getOwnerUrl()
    {
        return $this->raw['user']['html_url'];
    }

    public function getType()
    {
        return isset($this->raw['pull_request']) ? 'pull' : 'issue';
    }

    public function getRaw($key = null)
    {
        if ($key && array_key_exists($key, $this->raw)) {
            return $this->raw[$key];
        }

        return $this->raw;
    }

}
