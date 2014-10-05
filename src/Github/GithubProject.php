<?php

namespace Rs\Issues\Github;

use Github\Client;
use Github\ResultPager;
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
     * @return array
     */
    public function getBadges()
    {
        $badges = array();

        if ($this->useTravis()) {
            $badges[] = array(
                'img' => 'https://travis-ci.org/'.$this->raw['full_name'].'.svg',
                'link' => 'https://travis-ci.org/'.$this->raw['full_name']
            );
        }

        if ($composer = $this->getComposerName()) {
            $badges[] = array(
                'img'  => 'https://poser.pugx.org/' . $composer . '/version.svg',
                'link' => 'https://packagist.org/packages/' . $composer
            );
            $badges[] = array(
                'img' => 'https://poser.pugx.org/'.$composer.'/d/total.svg',
                'link' => 'https://packagist.org/packages/'.$composer
            );
        }

        return $badges;
    }

    private function useTravis()
    {
        try {
            $travis = $this->client->repos()->contents()->show($this->raw['owner']['login'], $this->raw['name'], '.travis.yml');
            if ('base64' === $travis['encoding']) {
                return true;
            }
        } catch (\Exception $e) {
            //no .travis.yml found
        }

        return false;
    }

    private function getComposerName()
    {
        try {
            $composer = $this->client->repos()->contents()->show($this->raw['owner']['login'], $this->raw['name'], 'composer.json');
            if ('base64' === $composer['encoding']) {
                $composer = json_decode(base64_decode($composer['content']));

                return isset($composer->name) ? $composer->name : null;
            }
        } catch (\Exception $e) {
            //no composer.json found
        }

        return null;
    }
}
