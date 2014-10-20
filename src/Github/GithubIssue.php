<?php

namespace Rs\Issues\Github;

use Rs\Issues\Issue;

/**
 * GithubIssue
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class GithubIssue implements Issue
{
    private $raw = [];

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
        return \igorw\get_in($this->raw, ['html_url']);
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return \igorw\get_in($this->raw, ['title']);
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return \igorw\get_in($this->raw, ['body']);
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt()
    {
        return new \DateTime(\igorw\get_in($this->raw, ['created_at']));
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
        return \igorw\get_in($this->raw, ['state']);
    }

    /**
     * @inheritdoc
     */
    public function getCommentCount()
    {
        return \igorw\get_in($this->raw,['comments']);
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
        return \igorw\get_in($this->raw, ['assignee', 'login']);
    }

    /**
     * @inheritdoc
     */
    public function getAssigneeUrl()
    {
        if ($this->getAssignee()) {
            return \igorw\get_in($this->raw, ['assignee', 'html_url']);
        }
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return \igorw\get_in($this->raw, ['number']);
    }

    /**
     * @inheritdoc
     */
    public function getAuthor()
    {
        return \igorw\get_in($this->raw, ['user', 'login']);
    }

    /**
     * @inheritdoc
     */
    public function getAuthorUrl()
    {
        return \igorw\get_in($this->raw, ['user', 'html_url']);
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
        $labels = \igorw\get_in($this->raw, ['labels'], []);
        $return = [];

        if (function_exists('array_column')) {
            return array_column($labels, 'name');
        }

        foreach ($labels as $label) {
            $return[] = $label['name'];
        }

        return $return;
    }
}
