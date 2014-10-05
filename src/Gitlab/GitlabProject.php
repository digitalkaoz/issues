<?php

namespace Rs\Issues\Gitlab;

use Gitlab\Api\Issues;
use Gitlab\Api\MergeRequests;
use Gitlab\Api\Repositories;
use Gitlab\Client;
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
     * @return array
     */
    public function getBadges()
    {
        $badges = array();

        if ($composer = $this->getComposerName()) {
            $badges[] = array(
                'img'  => 'https://poser.pugx.org/' . $composer . '/version.png',
                'link' => 'https://packagist.org/packages/' . $composer
            );
            $badges[] = array(
                'img' => 'https://poser.pugx.org/'.$composer.'/d/total.png',
                'link' => 'https://packagist.org/packages/'.$composer
            );
        }

        return $badges;
    }

    private function getComposerName()
    {
        try {
            $api = $this->client->api('repositories');
            /** @var Repositories $api */
            $composer = $api->getFile($this->raw['path_with_namespace'], 'composer.json', 'master');
            if ('base64' === $composer['encoding']) {
                $composer = json_decode(base64_decode($composer['content']));

                return isset($composer->name) ? $composer->name : false;
            }
        } catch (\Exception $e) {
            //no composer.json found
        }

        return false;
    }
}
