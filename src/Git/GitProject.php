<?php

namespace Rs\Issues\Git;
use Rs\Issues\BadgeFactory;

/**
 * GitProject
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
abstract class GitProject
{
    /**
     * @var BadgeFactory
     */
    protected $badgeFactory;

    /**
     * @inheritdoc
     */
    public function getBadges()
    {
        $badges = [];

        if ($this->getFile('.travis.yml')) {
            $badges[] = $this->badgeFactory->getTravis($this->getName());
        }

        if ($composer = $this->getFile('composer.json')) {
            $composer = json_decode($composer, true);
            if (isset($composer['name'])) {
                $badges[] = $this->badgeFactory->getComposerDownloads($composer['name']);
                $badges[] = $this->badgeFactory->getComposerVersion($composer['name']);
            }
        }

        return $badges;
    }

}
