<?php


namespace Rs\Issues\Bitbucket;

use Bitbucket\API\Api;
use Bitbucket\API\Authentication\AuthenticationInterface;
use Bitbucket\API\Repositories\Issues;
use Bitbucket\API\Repositories\PullRequests;
use Bitbucket\API\Repositories\Src;
use Buzz\Message\Response;
use Rs\Issues\BadgeFactory;
use Rs\Issues\Git\GitProject;
use Rs\Issues\Issue;
use Rs\Issues\Project;

/**
 * BitbucketProject
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class BitbucketProject extends GitProject implements Project
{
    /**
     * @var array
     */
    private $raw = [];
    /**
     * @var AuthenticationInterface
     */
    private $auth;
    /**
     * @var BadgeFactory
     */
    protected $badgeFactory;

    /**
     * @param array                   $data
     * @param BadgeFactory            $badgeFactory
     * @param AuthenticationInterface $auth
     */
    public function __construct(array $data, BadgeFactory $badgeFactory, AuthenticationInterface $auth = null)
    {
        $this->raw = $data;
        $this->auth = $auth;
        $this->badgeFactory = $badgeFactory;
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
    public function getDescription()
    {
        return \igorw\get_in($this->raw, ['description']);
    }

    /**
     * @inheritdoc
     */
    public function getUrl()
    {
        return \igorw\get_in($this->raw, ['links', 'self', 'href']);
    }

    /**
     * @inheritdoc
     */
    public function getIssues(array $criteria = ['state' => 'OPEN'])
    {
        $issues = $this->findIssues(new Issues(), 'issue', $criteria);
        $merges = $this->findIssues(new PullRequests(), 'pull', $criteria);

        return array_merge($issues, $merges);
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return 'bitbucket';
    }

    /**
     * gets a file (content) from the repository
     *
     * @param  string $filename
     * @return string
     */
    protected function getFile($filename)
    {
        $api = new Src();

        if ($this->auth) {
            $api->setCredentials($this->auth);
        }

        try {
            $file = $api->raw($this->raw['owner']['username'], $this->raw['name'], 'master', $filename);
            /** @var Response $file */
            if ($file->isSuccessful()) {
                return $file->getContent();
            }
        } catch (\Exception $e) {
            //file not found
        }
    }

    /**
     * @param  Issues|PullRequests $api
     * @param  string              $type
     * @param  array               $criteria
     * @return Issue[]
     */
    private function findIssues(Api $api, $type, array $criteria)
    {
        if ($this->auth) {
            $api->setCredentials($this->auth);
        }

        list($username, $repo) = explode('/', $this->getName());
        $issues = json_decode($api->all($username, $repo, $criteria)->getContent(), true);
        $newIssues = array();

        foreach ((array) $issues['issues'] as $issue) {
            if ('open' != $issue['status'] && 'new' != $issue['status']) {
                continue;
            }
            $newIssues[] = new BitbucketIssue($issue, $type, $this->getUrl());
        }

        return $newIssues;
    }
}
