<?php

namespace Rs\Issues;

/**
 * Issue.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
interface Issue
{
    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @return \DateTime|null
     */
    public function getClosedAt();

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt();

    /**
     * @return string
     */
    public function getState();

    /**
     * @return int
     */
    public function getCommentCount();

    /**
     * @return string
     */
    public function getAssignee();

    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getAuthor();

    /**
     * @return string
     */
    public function getAuthorUrl();

    /**
     * @return string
     */
    public function getAssigneeUrl();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return array
     */
    public function getTags();
}
