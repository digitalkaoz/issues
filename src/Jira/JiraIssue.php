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
     * @return string
     */
    public function getUrl()
    {
        return $this->raw->getSelf();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->raw->getSummary();
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->raw->getDescription();
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return new \DateTime($this->raw->getCreated());
    }

    /**
     * @return \DateTime|null
     */
    public function getClosedAt()
    {
        return $this->raw->getResolutionDate() ? new \DateTime($this->raw->getResolutionDate()) : null;
    }

    /**
     * @return string
     */
    public function getState()
    {
        $state = $this->raw->getStatus();

        return $state['name'];
    }

    /**
     * @return int
     */
    public function getCommentCount()
    {
        $comment = $this->raw->get('comment');

        return $comment['total'];
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        // TODO: Implement getUpdatedAt() method.
    }

    public function getAssignee()
    {
        // TODO: Implement getAssignee() method.
    }

    public function getId()
    {
        // TODO: Implement getNumber() method.
    }

    public function getAuthor()
    {
        // TODO: Implement getOwner() method.
    }

    public function getAuthorUrl()
    {
        // TODO: Implement getOwnerUrl() method.
    }

    public function getAssigneeUrl()
    {
        // TODO: Implement getAssigneeUrl() method.
    }

    public function getType()
    {
        return 'issue';
    }

    public function getTags()
    {
        //TODO
        return array();
    }
}
