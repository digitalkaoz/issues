<?php
/**
 * issues
 */

namespace Rs\Issues\Gitlab;

use Rs\Issues\Issue;

/**
 * GitlabIssue
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
     * @param array $data
     * @param       $type
     * @param       $url
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

        return sprintf('%s/%s/%d', $this->url, $path, $this->getNumber());
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
        return $this->raw['state']; //TODO use own consts?!
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

    public function getAssignee()
    {
        return $this->raw['assignee']['username'];
    }

    public function getAssigneeUrl()
    {
        $base = parse_url($this->url, PHP_URL_HOST);
        $proto = parse_url($this->url, PHP_URL_SCHEME);

        return sprintf('%s://%s/u/%s', $proto, $base, $this->getAssignee());
    }

    public function getNumber()
    {
        return $this->raw['iid'];
    }

    public function getOwner()
    {
        return $this->raw['author']['username'];
    }

    public function getOwnerUrl()
    {
        $base = parse_url($this->url, PHP_URL_HOST);
        $proto = parse_url($this->url, PHP_URL_SCHEME);

        return sprintf('%s://%s/u/%s', $proto, $base, $this->getOwner());
    }

    public function getType()
    {
        return $this->type;
    }

    public function getRaw($key = null)
    {
        if ($key && array_key_exists($key, $this->raw)) {
            return $this->raw[$key];
        }

        return $this->raw;
    }

}
