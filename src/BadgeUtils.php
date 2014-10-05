<?php

namespace Rs\Issues;

/**
 * BadgeUtils
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class BadgeUtils
{
    /**
     * @param  string $name
     * @param  bool   $useTravis
     * @param  string $composerName
     * @return array
     */
    public static function getBadges($name, $useTravis = false, $composerName = null)
    {
        $badges = array();

        if ($useTravis) {
            $badges[] = array(
                'img'  => 'https://travis-ci.org/' . $name . '.svg',
                'link' => 'https://travis-ci.org/' . $name
            );
        }

        if ($composerName) {
            $badges[] = array(
                'img'  => 'https://poser.pugx.org/' . $composerName . '/version.svg',
                'link' => 'https://packagist.org/packages/' . $composerName
            );
            $badges[] = array(
                'img'  => 'https://poser.pugx.org/' . $composerName . '/d/total.svg',
                'link' => 'https://packagist.org/packages/' . $composerName
            );
        }

        return $badges;
    }
}
