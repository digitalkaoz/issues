<?php

namespace Rs\Issues\Utils;

use Rs\Issues\Badge;

/**
 * BadgeFactory.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class BadgeFactory
{
    /**
     * @param string $name
     *
     * @return Badge
     */
    public function getComposerVersion($name)
    {
        return new Badge('https://poser.pugx.org/' . $name . '/version.svg', 'https://packagist.org/packages/' . $name);
    }

    /**
     * @param string $name
     *
     * @return Badge
     */
    public function getComposerDownloads($name)
    {
        return new Badge('https://poser.pugx.org/' . $name . '/d/total.svg', 'https://packagist.org/packages/' . $name);
    }

    /**
     * @param string $name
     *
     * @return Badge
     */
    public function getTravis($name)
    {
        return new Badge('https://travis-ci.org/' . $name . '.svg', 'https://travis-ci.org/' . $name);
    }
}
