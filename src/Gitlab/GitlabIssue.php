<?php

namespace Rs\Issues\Gitlab;

use Rs\Issues\Issue;
use Rs\Issues\Issue\ArrayIssue;

/**
 * GitlabIssue.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class GitlabIssue extends ArrayIssue implements Issue
{
    protected $paths = [
        //'url'          => [],
        'title'      => ['title'],
        'desc'       => ['description'],
        'created_at' => ['created_at'],
        'updated_at' => ['updated_at'],
        'closed_at'  => ['closed_at'],
        'state'      => ['state'],
        //'comments'     => [],
        'assignee' => ['assignee', 'username'],
        //'assignee_url' => [],
        'author' => ['author', 'username'],
        //'author_url'   => [],
        'id' => ['iid'],
        //'type'         => [],
        'tags' => ['labels'],
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
     * @param array  $data
     * @param string $type
     * @param string $url
     */
    public function __construct(array $data, $type, $url)
    {
        $this->raw  = $data;
        $this->type = $type;
        $this->url  = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        $path = $this->type === 'issue' ? 'issues' : 'merge_requests';

        return sprintf('%s/%s/%d', $this->url, $path, $this->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getCommentCount()
    {
        //TODO
    }

    /**
     * {@inheritdoc}
     */
    public function getAssigneeUrl()
    {
        if ($this->getAssignee()) {
            return $this->getUserUrl($this->getAssignee());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorUrl()
    {
        return $this->getUserUrl($this->getAuthor());
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $username
     *
     * @return string
     */
    private function getUserUrl($username)
    {
        $base  = parse_url($this->url, PHP_URL_HOST);
        $proto = parse_url($this->url, PHP_URL_SCHEME);

        return sprintf('%s://%s/u/%s', $proto, $base, $username);
    }
}
