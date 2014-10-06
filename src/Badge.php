<?php

namespace Rs\Issues;

/**
 * Badge
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Badge
{
    private $image;
    private $link;

    /**
     * @param string $image
     * @param string $link
     */
    public function __construct($image, $link)
    {
        $this->image = $image;
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array('img' => $this->image, 'link' => $this->link);
    }
}