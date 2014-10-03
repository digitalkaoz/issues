<?php

namespace Rs\Issues\Gitlab;

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
     * @var array
     */
    private $issues = array();

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

        $issues = $this->client->api('issues')->all($this->getName(),1 , 9999, $criteria);

        $newIssues = array();

        foreach ((array) $issues as $issue) {
            if ('closed' === $issue['state']) {
                continue;
            }
            $newIssues[] = new GitlabIssue($issue, 'issue', $this->getUrl());
        }

        $issues = $this->client->api('merge_requests')->opened($this->getName(),1 , 9999);

        foreach ((array) $issues as $issue) {
            $newIssues[] = new GitlabIssue($issue, 'merge', $this->getUrl());
        }

        return $this->issues = $newIssues;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->raw['path_with_namespace'];
    }

    public function getRaw($key = null)
    {
        if ($key && array_key_exists($key, $this->raw)) {
            return $this->raw[$key];
        }

        return $this->raw;
    }

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
            $composer = $this->client->api('repositories')->getFile($this->raw['path_with_namespace'], 'composer.json');
            if ('base64' === $composer['encoding']) {
                $composer = json_decode(base64_decode($composer['content']));

                return isset($composer->name) ? $composer->name : false;
            }
        } catch (\Exception $e) {
        }

        return false;
    }
}
