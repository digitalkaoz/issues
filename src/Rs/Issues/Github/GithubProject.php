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

        foreach ((array) $issues as $issue) {
            $newIssues[] = new GithubIssue($issue);
        }

        return $this->issues = $newIssues;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->raw['full_name'];
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
        return 'github';
    }

    /**
     * @return array
     */
    public function getBadges()
    {
        $badges = [];

        if ($travis = $this->getTravisName()) {
            $badges[] = array(
                'img' => 'https://secure.travis-ci.org/'.$this->raw['full_name'].'.png',
                'link' => 'http://travis-ci.org/'.$this->raw['full_name']
            );
        }

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

    private function getTravisName()
    {
        try {
            $travis = $this->client->repos()->contents()->show($this->raw['owner']['login'], $this->raw['name'], '.travis.yml');
            if ('base64' === $travis['encoding']) {
                return true;
            }
        } catch (\Exception $e) {
        }

        return false;
    }

    private function getComposerName()
    {
        try {
            $composer = $this->client->repos()->contents()->show($this->raw['owner']['login'], $this->raw['name'], 'composer.json');
            if ('base64' === $composer['encoding']) {
                $composer = json_decode(base64_decode($composer['content']));

                return isset($composer->name) ? $composer->name : false;
            }
        } catch (\Exception $e) {
        }

        return false;
    }
}
