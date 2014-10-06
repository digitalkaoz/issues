<?php

namespace Rs\Issues\Gitlab;

use Gitlab\Api\Issues;
use Gitlab\Api\MergeRequests;
use Gitlab\Api\Repositories;
use Gitlab\Client;
use Rs\Issues\BadgeFactory;
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
     * @param array  $data
     * @param Client $client
     */
    public function __construct(array $data, Client $client)
    {
        $this->raw = $data;
        $this->client = $client;
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

        $api = $this->client->api('issues');
        /** @var Issues $api */
        $issues = $api->all($this->getName(),1 , 9999, $criteria);

        $newIssues = array();

        foreach ((array) $issues as $issue) {
            if ('closed' === $issue['state']) {
                continue;
            }
            $newIssues[] = new GitlabIssue($issue, 'issue', $this->getUrl());
        }

        $api = $this->client->api('merge_requests');
        /** @var MergeRequests $api */
        $issues = $api->opened($this->getName(),1 , 9999);

        foreach ((array) $issues as $issue) {
            $newIssues[] = new GitlabIssue($issue, 'merge', $this->getUrl());
        }

        return $newIssues;
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
    public function getBadges(BadgeFactory $factory = null)
    {
        $badges = array();

        if (!$factory) {
            return $badges;
        }

        if ($this->getFile('.travis.yml')) {
            $badges[] = $factory->getTravis($this->getName());
        }

        if ($composer = $this->getFile('composer.json')) {
            $composer = json_decode($composer, true);
            $badges[] = $factory->getComposerDownloads($composer['name']);
            $badges[] = $factory->getComposerVersion($composer['name']);
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
}
