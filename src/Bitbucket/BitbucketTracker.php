<?php


namespace Rs\Issues\Bitbucket;

use Bitbucket\API\Authentication\Basic;
use Bitbucket\API\Repositories;
use Rs\Issues\Git\GitTracker;
use Rs\Issues\Tracker;

/**
 * BitbucketTracker
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class BitbucketTracker extends GitTracker implements Tracker
{
    /**
     * @var Basic
     */
    private $auth;

    /**
     * @param $user
     * @param $password
     */
    public function __construct($user = null, $password = null)
    {
        if ($user && $password) {
            $this->auth = new Basic($user, $password);
        }

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    public function getProject($name)
    {
        return $this->requestProject($name, function ($name) {
            list($username, $repo) = explode('/', $name);
            $api = new Repositories\Repository();
            if ($this->auth) {
                $api->setCredentials($this->auth);
            }

            $data = json_decode($api->get($username, $repo)->getContent(), true);

            if (isset($data['error'])) {
                throw new \InvalidArgumentException($data['error']['message']);
            }

            return $data;
        }, function ($data) {
            return new BitbucketProject($data, $this->badgeFactory, $this->auth);
        });
    }

    /**
     * @inheritdoc
     */
    public function findProjects($name)
    {
        return $this->requestProjects($name, function ($name) {
            list($user, ) = explode('/', $name);
            $api = new Repositories();
            if ($this->auth) {
                $api->setCredentials($this->auth);
            }

            return $api->all($user);
        }, 'full_name');
    }
}
