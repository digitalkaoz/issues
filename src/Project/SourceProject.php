<?php

namespace Rs\Issues\Project;

use Rs\Issues\BadgeFactory;

/**
 * SourceProject
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
abstract class SourceProject
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

    abstract protected function getFile($filename);
}
