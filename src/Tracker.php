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
     * @param  string  $username
     * @param  string  $password
     * @param  string  $host
     * @return boolean
     */
    public function connect($username = null, $password = null, $host = null);

    /**
     * @param  string  $name
     * @return Project
     */
    public function getProject($name);

}
