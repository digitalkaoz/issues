issues
======

a PHP wrapper for various issue tracker

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

Trackers
--------

currently these are the supported Trackers:

```php
<?php

$github = new GithubTracker($token = null);

$jira   = new JiraTracker($host, $username = null, $password = null);

$gitlab = new GitlabTracker($host, $token = null);
```


Usage
-----

The Library contains a simple Application to search various Trackers:

```
$ bin/issues search -u TOKEN github digitalkaoz/issues                     # search github
$ bin/issues search -u TOKEN -h gitlab.domain.com gitlab foo/*             # search gitlab
$ bin/issues search -u USER -p PWD -d https://jira.domain.com jira PROJKEY # search jira
```

to use it programmatic:

```php
<?php

$tracker = new GithubTracker($token); // or any other Tracker

$project = $tracker->getProject('digitalkaoz/issues'); //Rs/Issues/Project
$projects = $tracker->findProjects('digitalkaoz/*'); //Rs/Issues/Project[]

$issues = $project->getIssues(); //Rs/Issues/Issue[]
```

Searching
=========

you can either search for an concrete repository like `digitalkaoz/issues` or search for issues:

* `digitalkaoz/*` : all repos of `digitalkaoz`
* `symfony/[Console|Debug]+$` : only `symfony/Console` or `symfony/Debug`
* `doctrine/(?!common|lexer)([a-z0-9\.-]+)$` all but `doctrine/common` and `doctrine/lexer`

The CLI Application searches by default, by Code you should use `findProjects` instead of `getProject`

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
