<?php
/**
 * issues
 */

namespace Rs\Issues\Gitlab;

use Rs\Issues\Issue;

/**
 * GitlabIssue
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class GitlabIssue implements Issue
{
    private $raw = array();
    /**
     * @var
     */
    private $type;
    /**
     * @var
     */
    private $url;

    /**
     * @param array  $data
     * @param string $type
     * @param string $url
     */
    public function __construct(array $data, $type, $url)
    {
        $this->raw = $data;
        $this->type = $type;
        $this->url = $url;
    }

    /**
     * @inheritdoc
     */
    public function getUrl()
    {
        $path = $this->type == 'issue' ? 'issues' : 'merge_requests';

        return sprintf('%s/%s/%d', $this->url, $path, $this->getId());
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
        return $this->raw['description'];
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
        //return $this->raw['comments'];
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
        return $this->raw['assignee']['username'];
    }

    /**
     * @inheritdoc
     */
    public function getAssigneeUrl()
    {
        return $this->getUserUrl($this->getAssignee());
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->raw['iid'];
    }

    /**
     * @inheritdoc
     */
    public function getAuthor()
    {
        return $this->raw['author']['username'];
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @inheritdoc
     */
    public function getTags()
    {
        return isset($this->raw['labels']) ? $this->raw['labels'] : array();
    }

    /**
     * @param  string $username
     * @return string
     */
    private function getUserUrl($username)
    {
        $base = parse_url($this->url, PHP_URL_HOST);
        $proto = parse_url($this->url, PHP_URL_SCHEME);

        return sprintf('%s://%s/u/%s', $proto, $base, $username);
    }
}
