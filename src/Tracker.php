<?php

namespace Rs\Issues;

use Rs\Issues\Exception\NotFoundException;
use Rs\Issues\Utils\RepositoryParser;
use Rs\Issues\Utils\BadgeFactory;

/**
 * Interface for all Tracker
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
interface Tracker
{
    /**
     * @param  string            $name
     * @return Project
     * @throws NotFoundException
     */
    public function getProject($name);

    /**
     * @param $name
     * @return Project[]
     */
    public function findProjects($name);

    /**
     * overwrite the repository parser
     *
     * @param  RepositoryParser $parser
     * @return void
     */
    public function setRepositoryParser(RepositoryParser $parser);

    /**
     * overwrite the bade factory
     *
     * @param  BadgeFactory $factory
     * @return void
     */
    public function setBadgeFactory(BadgeFactory $factory);
}
