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
        return $this->raw['state'];
    }

    /**
     * @inheritdoc
     */
    public function getCommentCount()
    {
        return $this->raw['comments'];
    }

    public function getRaw($key = null)
    {
        if ($key && array_key_exists($key, $this->raw)) {
            return $this->raw[$key];
        }

        return $this->raw;
    }
}
