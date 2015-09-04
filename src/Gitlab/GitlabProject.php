<?php

namespace Rs\Issues\Gitlab;

use Gitlab\Api\ApiInterface;
use Gitlab\Api\Issues;
use Gitlab\Api\MergeRequests;
use Gitlab\Api\Repositories;
use Gitlab\Client;
use Rs\Issues\Issue;
use Rs\Issues\Project;
use Rs\Issues\Project\SourceProject;
use Rs\Issues\Utils\BadgeFactory;

/**
 * GitlabProject.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class GitlabProject extends SourceProject implements Project
{
    protected $paths = [
        'url'  => ['web_url'],
        'name' => ['path_with_namespace'],
        'desc' => ['description'],
    ];

    /**
     * @var Client
     */
    private $client;

    /**
     * @param array        $data
     * @param Client       $client
     * @param BadgeFactory $badgeFactory
     */
    public function __construct(array $data, Client $client, BadgeFactory $badgeFactory)
    {
        $this->raw          = $data;
        $this->client       = $client;
        $this->badgeFactory = $badgeFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getIssues(array $criteria = [])
    {
        $issues = $this->findIssues($this->client->api('issues'), 'issue');
        $merges = $this->findIssues($this->client->api('merge_requests'), 'merge', 'opened');

        return array_merge($issues, $merges);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'gitlab';
    }

    /**
     * gets a file (content) from the repository.
     *
     * @param string $filename
     *
     * @return string
     */
    protected function getFile($filename)
    {
        try {
            $api = $this->client->api('repositories');
            /* @var Repositories $api */
            $file = $api->getFile($this->raw['path_with_namespace'], $filename, 'master');
            if ('base64' === $file['encoding']) {
                return base64_decode($file['content'], true);
            }
        } catch (\Exception $e) {
            //file not found
        }
    }

    /**
     * @param ApiInterface $api
     * @param string       $type
     * @param string       $method
     *
     * @return \Rs\Issues\Issue[]
     */
    private function findIssues(ApiInterface $api, $type, $method = 'all')
    {
        /* @var Issues|MergeRequests $api */
        $issues = $api->$method($this->getName(), 1, 9999);

        $newIssues = [];

        foreach ((array) $issues as $issue) {
            if ('closed' === $issue['state']) {
                continue;
            }
            $newIssues[] = new GitlabIssue($issue, $type, $this->getUrl());
        }

        return $newIssues;
    }
}
