<?php


namespace Rs\Issues\Bitbucket;

use Bitbucket\API\Api;
use Bitbucket\API\Authentication\Basic;
use Bitbucket\API\Repositories;
use Rs\Issues\Tracker\SearchableTracker;
use Rs\Issues\Tracker;

/**
 * BitbucketTracker
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class BitbucketTracker extends SearchableTracker implements Tracker
{
    /**
     * @var Api
     */
    private $api;

    /**
     * @param $user
     * @param $password
     */
    public function __construct($user = null, $password = null, Api $api = null)
    {
        $this->api = $api ?: new Api();

        if ($user && $password) {
            $this->api->setCredentials(new Basic($user, $password));
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
            $api = $this->api->api('Repositories\Repository');
            /** @var Repositories\Repository $api */

            $data = json_decode($api->get($username, $repo)->getContent(), true);

            if (isset($data['error'])) {
                throw new \InvalidArgumentException($data['error']['message']);
            }

            return $data;
        }, function ($data) {
            return new BitbucketProject($data, $this->api, $this->badgeFactory);
        });
    }

    /**
     * @inheritdoc
     */
    public function findProjects($name)
    {
        return $this->requestProjects($name, function ($name) {
            list($user, ) = explode('/', $name);
            $api = $this->api->api('Repositories');
            /** @var Repositories $api */

            $data = json_decode($api->all($user)->getContent(), true);

            if (isset($data['error'])) {
                throw new \InvalidArgumentException($data['error']['message']);
            }

            return $data;
        }, 'full_name');
    }
}
