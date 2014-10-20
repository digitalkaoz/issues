<?php

namespace Rs\Issues\Gitlab;

use Gitlab\Api\ApiInterface;
use Gitlab\Api\Issues;
use Gitlab\Api\MergeRequests;
use Gitlab\Api\Repositories;
use Gitlab\Client;
use Rs\Issues\BadgeFactory;
use Rs\Issues\Git\GitProject;
use Rs\Issues\Issue;
use Rs\Issues\Project;

/**
 * GitlabProject
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class GitlabProject extends GitProject implements Project
{
    private $raw = array();

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
        $this->raw = $data;
        $this->client = $client;
        $this->badgeFactory = $badgeFactory;
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
    public function getUrl()
    {
        return $this->raw['web_url'];
    }

    /**
     * @inheritdoc
     */
    public function getIssues(array $criteria = array())
    {
        $issues = $this->findIssues($this->client->api('issues'), 'issue');
        $issues = array_merge($issues, $this->findIssues($this->client->api('merge_requests'), 'merge'));

        return $issues;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->raw['path_with_namespace'];
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return 'gitlab';
    }

    /**
     * gets a file (content) from the repository
     *
     * @param  string $filename
     * @return string
     */
    protected function getFile($filename)
    {
        try {
            $api = $this->client->api('repositories');
            /** @var Repositories $api */
            $file = $api->getFile($this->raw['path_with_namespace'], $filename, 'master');
            if ('base64' === $file['encoding']) {
                return base64_decode($file['content']);
            }
        } catch (\Exception $e) {
            //file not found
        }
    }

    /**
     * @param Issues|MergeRequests $api
     * @param string $type
     * @return Issue[]
     */
    private function findIssues(ApiInterface $api, $type)
    {
        $issues = $api->all($this->getName(), 1, 9999);

        $newIssues = array();

        foreach ((array) $issues as $issue) {
            if ('closed' === $issue['state']) {
                continue;
            }
            $newIssues[] = new GitlabIssue($issue, $type, $this->getUrl());
        }

        return $newIssues;
    }
}
