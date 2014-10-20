<?php

namespace Rs\Issues\Github;

use Rs\Issues\GenericIssue;
use Rs\Issues\Issue;

/**
 * GithubIssue
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class GithubIssue extends GenericIssue implements Issue
{
    protected $paths = [
        'url'          => ['html_url'],
        'title'        => ['title'],
        'desc'         => ['body'],
        'created_at'   => ['created_at'],
        'updated_at'   => ['updated_at'],
        'closed_at'    => ['closed_at'],
        'state'        => ['state'],
        'comments'     => ['comments'],
        'assignee'     => ['assignee', 'login'],
        'assignee_url' => ['assignee', 'html_url'],
        'author'       => ['user', 'login'],
        'author_url'   => ['user', 'html_url'],
        'id'           => ['number'],
        'type'         => ['pull_request'],
        'tags'         => ['labels'],
    ];

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
    public function getType()
    {
        return $this->attr('type') ? 'pull' : 'issue';
    }

    /**
     * @inheritdoc
     */
    public function getTags()
    {
        $labels = $this->attr('tags', []);
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
