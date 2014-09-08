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
     * @return Issue[]
     */
    public function getIssues(array $criteria = array());
}
