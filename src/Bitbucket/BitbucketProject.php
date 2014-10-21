<?php


namespace Rs\Issues\Bitbucket;

use Bitbucket\API\Api;
use Bitbucket\API\Repositories\Issues;
use Bitbucket\API\Repositories\PullRequests;
use Bitbucket\API\Repositories\Src;
use Buzz\Message\Response;
use Rs\Issues\Issue;
use Rs\Issues\Project;
use Rs\Issues\Project\SourceProject;
use Rs\Issues\Utils\BadgeFactory;

/**
 * BitbucketProject
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class BitbucketProject extends SourceProject implements Project
{
    protected $paths = [
        'url'  => ['links', 'self', 'href'],
        'name' => ['full_name'],
        'desc' => ['description'],
    ];

    /**
     * @var Api
     */
    private $api;
    /**
     * @var BadgeFactory
     */
    protected $badgeFactory;

    /**
     * @param array        $data
     * @param Api          $api
     * @param BadgeFactory $badgeFactory
     */
    public function __construct(array $data, Api $api, BadgeFactory $badgeFactory)
    {
        $this->raw = $data;
        $this->api = $api;
        $this->badgeFactory = $badgeFactory;
    }

    /**
     * @inheritdoc
     */
    public function getIssues(array $criteria = ['state' => 'OPEN'])
    {
        $issues = $this->findIssues($this->api->api('Repositories\Issues'), 'issue', $criteria);
        $merges = $this->findIssues($this->api->api('Repositories\PullRequests'), 'pull', $criteria);

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
        $api = $this->api->api('Repositories\Src');
        /** @var Src $api */

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
     * @param  Api     $api
     * @param  string  $type
     * @param  array   $criteria
     * @return Issue[]
     */
    private function findIssues(Api $api, $type, array $criteria)
    {
        /** @var Issues|PullRequests $api */

        list($username, $repo) = explode('/', $this->getName());
        $issues = json_decode($api->all($username, $repo, $criteria)->getContent(), true);
        $newIssues = [];

        $key = 'issue' == $type ? 'issues' : 'values';

        foreach ((array) $issues[$key] as $issue) {
            if ('open' != $issue['status'] && 'new' != $issue['status']) {
                continue;
            }

            $newIssues[] = new BitbucketIssue($issue, $type, $this->getUrl());
        }

        return $newIssues;
    }
}
