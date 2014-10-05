<?php

namespace Rs\Issues\Github;

use Github\Client;
use Github\ResultPager;
use Rs\Issues\BadgeUtils;
use Rs\Issues\Project;

/**
 * GithubProject
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class GithubProject implements Project
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
        return $this->raw['html_url'];
    }

    /**
     * @inheritdoc
     */
    public function getIssues(array $criteria = array())
    {
        if (!$criteria) {
            $criteria = array('state' => 'open');
        }

        list($username, $repo) = explode('/', $this->getName());

        $pager = new ResultPager($this->client);

        $issues = $pager->fetchAll($this->client->issue(), 'all', array($username, $repo, $criteria));

        $newIssues = array();

        foreach ($issues as $issue) {
            $newIssues[] = new GithubIssue($issue);
        }

        return $newIssues;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->raw['full_name'];
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return 'github';
    }

    /**
     * @inheritdoc
     */
    public function getBadges()
    {
        return BadgeUtils::getBadges($this->getName(), !!$this->getFile('.travis.yml'), $this->getComposerName());
    }

    private function getComposerName()
    {
        $file = $this->getFile('composer.json');

        return $file && isset($file->name) ? $file->name : null;
    }

    private function getFile($filename)
    {
        try {
            $file = $this->client->repos()->contents()->show($this->raw['owner']['login'], $this->raw['name'], $filename);
            if ('base64' === $file['encoding']) {
                return json_decode(base64_decode($file['content']));
            }
        } catch (\Exception $e) {
            //file not found
        }
    }
}
