<?php

namespace Gitlab;

use Rs\Issues\Badge;
use Rs\Issues\BadgeFactory;
use Rs\Issues\ComposerBadgeFactory;

/**
 * GitlabBadgeFactory
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class GitlabBadgeFactory extends ComposerBadgeFactory implements BadgeFactory
{

    /**
     * @param array $data
     * @return Badge[]
     */
    public function getBadges(array $data)
    {
        $badges = array();

        if ($composerFile = $this->getFile($data['client'], 'composer.json', $data['name'])) {
            $composerBadges = $this->getComposerBadges($composerFile->name);

            $badges += $composerBadges;
        }

        return $badges;
    }

    /**
     * @param Client $client
     * @param string $filename
     * @param string $name
     * @return string
     */
    private function getFile(Client $client, $filename, $name)
    {
        try {
            $api = $client->api('repositories');
            /** @var Repositories $api */
            $file = $api->getFile($name, $filename, 'master');
            if ('base64' === $file['encoding']) {
                return json_decode(base64_decode($file['content']));
            }
        } catch (\Exception $e) {
            //file not found
        }
    }
}