<?php

namespace Rs\Issues;

/**
 * Interface for all Projects
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
interface Project
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @param  array   $criteria an array of attributes and their values to search for, depends on the concrete tracker
     * @return Issue[]
     */
    public function getIssues(array $criteria = array());

    /**
     * @return string
     */
    public function getType();

    /**
     * @param  BadgeFactory $factory
     * @return Badge[]
     */
    public function getBadges(BadgeFactory $factory = null);

}
