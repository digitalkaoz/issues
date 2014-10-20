<?php

namespace Rs\Issues\Bitbucket;

use Rs\Issues\GenericIssue;
use Rs\Issues\Issue;

/**
 * BitbucketIssue
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class BitbucketIssue extends GenericIssue implements Issue
{
    protected $paths = [
        //'url'          => [],
        'title'        => ['title'],
        'desc'         => ['content'],
        'created_at'   => ['created_on'],
        'updated_at'   => ['utc_last_updated'],
        'closed_at'    => ['closed_on'],
        'state'        => ['state'],
        'comments'     => ['comment_count'],
        'assignee'     => ['responsible', 'username'],
        //'assignee_url' => [],
        'author'       => ['reported_by', 'username'],
        //'author_url'   => [],
        'id'           => ['local_id'],
        //'type'         => [],
        //'tags'         => [],
    ];

    /**
     * @var
     */
    private $type;
    /**
     * @var
     */
    private $url;

    /**
     * @param array  $raw
     * @param string $type
     * @param string $url
     */
    public function __construct(array $raw, $type, $url)
    {
        $this->raw = $raw;
        $this->type = $type;
        $this->url = $url;
    }

    /**
     * @inheritdoc
     */
    public function getUrl()
    {
        $path = $this->type == 'issue' ? 'issue' : 'pull_request';

        return sprintf('%s/%s/%d', $this->url, $path, $this->getId());
    }

    /**
     * @inheritdoc
     */
    public function getAuthorUrl()
    {
        $this->getUserUrl($this->getAuthor());
    }

    /**
     * @inheritdoc
     */
    public function getAssigneeUrl()
    {
        $this->getUserUrl($this->getAssignee());
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
        return [];
    }

    /**
     * @param  string $username
     * @return string
     */
    private function getUserUrl($username)
    {
        $base = parse_url($this->url, PHP_URL_HOST);
        $proto = parse_url($this->url, PHP_URL_SCHEME);

        return sprintf('%s://%s/%s', $proto, $base, $username);
    }
}
