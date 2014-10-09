<?php

namespace spec\Rs\Issues;

use PhpSpec\ObjectBehavior;

class RepositoryParserSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Rs\Issues\RepositoryParser');
    }

    public function it_returns_booleans_for_concrete_repos()
    {
        $this->isConcrete('digitalkaoz/issues')->shouldBe(true);
        $this->isConcrete('digitalkaoz/*')->shouldBe(false);
    }

    public function it_returns_booleans_for_wildcard_repos()
    {
        $this->isWildcard('digitalkaoz/*')->shouldBe(true);
        $this->isWildcard('digitalkaoz/issues')->shouldBe(false);
    }

    public function it_returns_booleans_for_regex_repos()
    {
        $this->matchesRegex('symfony/[Console|Debug]+$', 'symfony/Console')->shouldBe(true);
        $this->matchesRegex('symfony/[Console|Debug]+$', 'symfony/Foo')->shouldBe(false);

        $this->matchesRegex('doctrine/(?!common|lexer)([a-z0-9\.-]+)$', 'doctrine/dbal')->shouldBe(true);
        $this->matchesRegex('doctrine/(?!common|lexer)([a-z0-9\.-]+)$', 'doctrine/lexer')->shouldBe(false);
    }

}
