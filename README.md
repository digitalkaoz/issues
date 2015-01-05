issues
======

a PHP wrapper for various issue tracker

[![Build Status](https://img.shields.io/travis/digitalkaoz/issues/master.svg?style=flat-square)](https://travis-ci.org/digitalkaoz/issues)
[![Dependency Status](https://img.shields.io/versioneye/d/php/digitalkaoz:issues.svg?style=flat-square)](https://www.versioneye.com/php/digitalkaoz:issues)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/digitalkaoz/issues.svg?style=flat-square)](https://scrutinizer-ci.com/g/digitalkaoz/issues/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/digitalkaoz/issues/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/digitalkaoz/issues/?branch=master)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/8b6776fe-d453-406b-a073-a6d4eeb9d4b4.svg?style=flat-square)](https://insight.sensiolabs.com/projects/8b6776fe-d453-406b-a073-a6d4eeb9d4b4)
[![Latest Stable Version](https://img.shields.io/packagist/v/digitalkaoz/issues.svg?style=flat-square)](https://packagist.org/packages/digitalkaoz/issues)
[![Total Downloads](https://img.shields.io/packagist/dt/digitalkaoz/issues.svg?style=flat-square)](https://packagist.org/packages/digitalkaoz/issues)


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

$bitbucket = new BitbucketTracker($username = null, $password = null);
```


Usage
-----

The Library contains a simple Application to search various Trackers:

```
$ bin/issues search -u TOKEN github digitalkaoz/issues                     # search github
$ bin/issues search -u TOKEN -h gitlab.domain.com gitlab foo/*             # search gitlab
$ bin/issues search -u USER -p PWD -d https://jira.domain.com jira PROJKEY # search jira
$ bin/issues search -u USER -p PWD -d bitbucket gentlero/bitbucket-api     # search bitbucket
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

The CLI Application searches by default, in your Code you should use `findProjects` instead of `getProject`

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
