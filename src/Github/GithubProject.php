<?php

namespace Rs\Issues\Github;

use Github\Client;
use Github\ResultPager;

use Rs\Issues\Utils\BadgeFactory;
use Rs\Issues\Project\SourceProject;
use Rs\Issues\Project;

/**
 * GithubProject
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class GithubProject extends SourceProject implements Project
{
    private $raw = [];

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
        return \igorw\get_in($this->raw, ['description']);
    }

    /**
     * @inheritdoc
     */
    public function getUrl()
    {
        return \igorw\get_in($this->raw, ['html_url']);
    }

    /**
     * @inheritdoc
     */
    public function getIssues(array $criteria = ['state' => 'open'])
    {
        list($username, $repo) = explode('/', $this->getName());

        $pager = new ResultPager($this->client);

        $issues = $pager->fetchAll($this->client->issue(), 'all', [$username, $repo, $criteria]);

        $newIssues = [];

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
        return \igorw\get_in($this->raw, ['full_name']);
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return 'github';
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
            $file = $this->client->repos()->contents()->show($this->raw['owner']['login'], $this->raw['name'], $filename);
            if ('base64' === $file['encoding']) {
                return base64_decode($file['content']);
            }
        } catch (\Exception $e) {
            //file not found
        }
    }
}
