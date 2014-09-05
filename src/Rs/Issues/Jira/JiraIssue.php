<?php
/**
 * issues
 */

namespace Rs\Issues\Jira;

use Rs\Issues\Issue;


/**
 * JiraIssue
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class JiraIssue implements Issue
{
    /**
     * @var \chobie\Jira\Issue
     */
    private $raw;

    /**
     * @param \chobie\Jira\Issue $issue
     */
    public function __construct(\chobie\Jira\Issue $issue)
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
    public function getText()
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
}