<?php

namespace Rs\Issues\Gitlab;

use Gitlab\Api\Issues;
use Gitlab\Api\MergeRequests;
use Gitlab\Api\Repositories;
use Gitlab\Client;
use Rs\Issues\BadgeFactory;
use Rs\Issues\Issue;
use Rs\Issues\Project;

/**
 * GitlabProject
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class GitlabProject implements Project
{
    private $raw = array();

    /**
     * @var Client
     */
    private $client;
    /**
     * @var BadgeFactory
     */
    private $badgeFactory;

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
        if (!$criteria) {
            $criteria = array('state' => 'open');
        }

        $issues = $this->findIssues($criteria);
        $issues = array_merge($issues, $this->findMergeRequests());

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
     * @inheritdoc
     */
    public function getBadges()
    {
        $badges = array();

        if ($this->getFile('.travis.yml')) {
            $badges[] = $this->badgeFactory->getTravis($this->getName());
        }

        if ($composer = $this->getFile('composer.json')) {
            $composer = json_decode($composer, true);
            $badges[] = $this->badgeFactory->getComposerDownloads($composer['name']);
            $badges[] = $this->badgeFactory->getComposerVersion($composer['name']);
        }

        return $badges;
    }

    /**
     * gets a file (content) from the repository
     *
     * @param  string $filename
     * @return string
     */
    private function getFile($filename)
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
     * @param  array   $criteria
     * @return Issue[]
     */
    private function findIssues(array $criteria)
    {
        $api = $this->client->api('issues');
        /** @var Issues $api */
        $issues = $api->all($this->getName(), 1, 9999, $criteria);

        $newIssues = array();

        foreach ((array) $issues as $issue) {
            if ('closed' === $issue['state']) {
                continue;
            }
            $newIssues[] = new GitlabIssue($issue, 'issue', $this->getUrl());
        }

        return $newIssues;
    }

    /**
     * @return Issue[]
     */
    private function findMergeRequests()
    {
        $api = $this->client->api('merge_requests');
        /** @var MergeRequests $api */

        if (method_exists($api, 'opened')) {
            $issues = $api->opened($this->getName(), 1, 9999);
        } else {
            //BC, should be removed once a new stable version of https://github.com/m4tthumphrey/php-gitlab-api is tagged
            $issues = $api->all($this->getName(), 1, 9999);
        }

        $newIssues = array();

        foreach ((array) $issues as $issue) {
            if ('closed' === $issue['state']) { //could be removed once lib is tagged
                continue;
            }
            $newIssues[] = new GitlabIssue($issue, 'merge', $this->getUrl());
        }

        return $newIssues;
    }
}
