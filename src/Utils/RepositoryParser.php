<?php

namespace Rs\Issues\Utils;

/**
 * RepositoryParser.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class RepositoryParser
{
    const CONCRETE = '/^[a-zA-Z0-9\.-]+\/[a-zA-Z0-9\.-]+/';
    const WILDCARD = '/^([a-zA-Z0-9\.-])+\/\*/';

    /**
     * checks if its a concrecte repo like "digitalkaoz/issues".
     *
     * @param string $name
     *
     * @return bool
     */
    public function isConcrete($name)
    {
        return (bool) preg_match(self::CONCRETE, $name);
    }

    /**
     * checks if its a wildcard repo like "digitalkaoz/*".
     *
     * @param string $name
     *
     * @return bool
     */
    public function isWildcard($name)
    {
        return (bool) preg_match(self::WILDCARD, $name);
    }

    /**
     * checks if its matches a regex
     * "symfony/[Console|Debug]+$" only "symfony/Console" or "symfony/Debug"
     * "doctrine/(?!common|lexer)([a-z0-9\.-]+)$" all but "doctrine/common" and "doctrine/lexer".
     *
     * @param string $name
     * @param string $pattern
     *
     * @return bool
     */
    public function matchesRegex($pattern, $name)
    {
        return (bool) preg_match('/' . str_replace('/', '\/', $pattern) . '/', $name);
    }
}
