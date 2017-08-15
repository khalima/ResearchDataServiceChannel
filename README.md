# Druid Drupal 8 project template

## About the site

- Configuration is in `conf` folder
- Custom modules are in `public/modules/custom` folder
- Custom themes are in `public/themes/custom` folder

### Contrib modules included

These recommended modules are included in the `composer.json`, but you can remove them if not needed.

- [Admin Toolbar](https://www.drupal.org/project/admin_toolbar) - Improved Drupal Toolbar
- [Pathauto](https://www.drupal.org/project/pathauto) - Automated URL alias generating
- [Swift Mailer](https://www.drupal.org/project/swiftmailer) - Advanced mailer
- [System Status](https://www.drupal.org/project/system_status) - Lumturio monitoring

## Build codebase

This will download Drupal core, contrib modules and setup configuration. See Composer section below.

```
$ composer build-dev
```

## Set-up the site

NOTE: You need to be inside working environment to do this (Vagrant, Docker or server) and in the root (aka where the
composer.json is).

After you have build the codebase, install the site with current configuration:

```
$ bin/drush -r public si config_installer
```

OR if you want to sync existing site e.g. from production/staging/testing:

```
$ tools/sync-to-local
```

## Build tools

### Composer

This template uses [Composer](https://getcomposer.org) to build the codebase.

Installations rely on composer.lock file. This can be updated by running `composer build-dev` which will update the
lock file. Lock file us used by testing/staging/production builds and will not be updated then.

Build codebase with dev requirements and update composer.lock file:

```
$ composer build-dev
```

Build codebase without dev requirements using composer.lock file and optimized autoloader:

```
$ composer build-production|build-testing|build-staging
```

### npm

We use npm's [`shrinkwrap`](https://docs.npmjs.com/cli/shrinkwrap) command to lock the list of installed packages. This 
ensures the exact same package versions being installed across different environments. 

See [package.json](package.json) and [npm-shrinkwrap.json](npm-shrinkwrap.json).

## Quality Assurance

### Tests

Run all tests and checks

```
$ composer test
```

Run unit tests (PHPUnit)

```
$ composer test-unit
```

Run static PHP checks

```
$ composer test-static-php
```

Run static JS checks

```
$ composer test-static-js
```

Run behavioral tests (Behat)

```
$ composer test-behavioral
```

#### PHPUnit

Drupal uses [PHPUnit](https://phpunit.de) for unit, integration, and behavioral tests. See the
[documentation](Drupal\FunctionalJavascriptTests) for more information about Drupal's usage of PHPUnit.

This template supports all PHPUnit-based tests, with the exception of `functional-javascript` tests (built on
`\Drupal\FunctionalJavascriptTests\JavaScriptTestBase`). Submodules are unsupported, because their tests cannot be
detected automatically.

#### Behat

[Behat](http://behat.org/) is used for behavioral testing. Its
configuration and the 
[features](http://docs.behat.org/en/latest/user_guide/features_scenarios.html)
to test are located in `./behat`.

### Automated builds

[Travis CI](http://travis-ci.com/) is used to run all automated analyses and tests for pull requests (PRs) and pushes.
Sensitive data **MUST** be [encrypted](https://docs.travis-ci.com/user/encrypting-files/). To instruct Travis CI to act on
changes to a particular repository, go to the *Integration & services* section of its settings page on Github, and
follow the instructions when adding *Travis CI* integration. Also update `env.global` in `./.travis.yml` with
project-specific values.
