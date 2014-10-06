<?php

namespace Rs\Issues\Github;

use Github\Client;
use Github\ResultPager;
use Rs\Issues\BadgeFactory;
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
     * @param array $data
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
     * @param string $filename
     * @return string
     */
    private function getFile($filename)
    {
        try {
            $file = $this->client->repos()->contents()->show($this->raw['owner']['login'], $this->raw['name'], $filename);
            if ('base64' === $file['encoding']) {
                return base64_decode($file['content']);
            }
        } catch (\Exception $e) {
            //file not found
        }
    }
}
