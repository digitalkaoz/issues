issues
======

a PHP wrapper for various issue tracker (github & jira atm)

[![Build Status](https://travis-ci.org/digitalkaoz/issues.svg?branch=master)](https://travis-ci.org/digitalkaoz/issues)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/digitalkaoz/issues/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/digitalkaoz/issues/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/digitalkaoz/issues/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/digitalkaoz/issues/?branch=master)

[![Latest Stable Version](https://poser.pugx.org/digitalkaoz/issues/version.svg)](https://packagist.org/packages/digitalkaoz/issues)
[![Latest Unstable Version](https://poser.pugx.org/digitalkaoz/issues/v/unstable.svg)](//packagist.org/packages/digitalkaoz/issues) 
[![Total Downloads](https://poser.pugx.org/digitalkaoz/issues/downloads.svg)](https://packagist.org/packages/digitalkaoz/issues)


Installation
------------

```bash
$ composer require digitalkaoz/issues
```


Usage
-----

The Library contains a simple Application:

```
$ bin/issues search github digitalkaoz/issues
```

or for jira:

```
$ bin/issues search -u USER -p PWD -d https://jira.domain.com jira PROJKEY
```

to use it programmaticly:

```php
<?php

$tracker = new GithubTracker(); // or new JiraTracker();
$tracker->connect($username, $password, $host); //if you want or need authentication
$project = $tracker->getProject('digitalkaoz/issues'); //Rs/Issues/Project
$issues = $project->getIssues(); //Rs/Issues/Issue[]

```

![Console Output](http://i57.tinypic.com/vrgfg2.png)

Phar
----

To build a standalone PHAR:

```
$ vendor/bin/box build
```

now you can use it as standalone app as follows:

```
$ php issues.phar search github digitalkaoz/issues
```

Tests
-----

```
$ vendor/bin/phpspec run
```
