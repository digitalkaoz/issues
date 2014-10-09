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
}
