<?php

namespace Rs\Issues;

/**
 * Interface for all Tracker
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
interface Tracker
{
    /**
     * @param  string  $name
     * @return Project
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
     * @param RepositoryParser $parser
     */
    public function setRepositoryParser(RepositoryParser $parser);

    /**
     * overwrite the bade factory
     *
     * @param BadgeFactory $factory
     */
    public function setBadgeFactory(BadgeFactory $factory);
}
