<?php


namespace Rs\Issues\Bitbucket;

use Rs\Issues\Issue;

/**
 * BitbucketIssue
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class BitbucketIssue implements Issue
{

    /**
     * @var array
     */
    private $raw = [];
    /**
     * @var
     */
    private $type;

    /**
     * @param array $raw
     */
    public function __construct(array $raw, $type)
    {
        $this->raw = $raw;
        $this->type = $type;
    }

    public function getUrl()
    {
        // TODO: Implement getUrl() method.
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
        return $this->raw['content'];
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt()
    {
        return $this->raw['created_on'] ? new \DateTime($this->raw['created_on']) : null;
    }

    /**
     * @inheritdoc
     */
    public function getClosedAt()
    {
        return $this->raw['closed_on'] ? new \DateTime($this->raw['closed_on']) : null;
    }

    /**
     * @inheritdoc
     */
    public function getUpdatedAt()
    {
        return $this->raw['utc_last_updated'] ? new \DateTime($this->raw['utc_last_updated']) : null;
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
        return $this->raw['comment_count'];
    }

    public function getAssignee()
    {
        // TODO: Implement getAssignee() method.
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->raw['local_id'];
    }

    /**
     * @inheritdoc
     */
    public function getAuthor()
    {
        return $this->raw['reported_by']['username'];
    }

    public function getAuthorUrl()
    {
        // TODO: Implement getAuthorUrl() method.
    }

    public function getAssigneeUrl()
    {
        // TODO: Implement getAssigneeUrl() method.
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @inheritdoc
     */
    public function getTags()
    {
        return array();
    }
}
