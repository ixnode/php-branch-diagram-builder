# PHPBranchDiagramBuilder

[![PHP](https://img.shields.io/badge/PHP-^7.4%20||%20^8.0-777bb3.svg?logo=php&logoColor=white&labelColor=555555&style=flat)](https://www.php.net/supported-versions.php)
[![Latest Stable Version](http://poser.pugx.org/ixnode/php-branch-diagram-builder/v)](https://packagist.org/packages/ixnode/php-branch-diagram-builder)
[![Total Downloads](http://poser.pugx.org/ixnode/php-branch-diagram-builder/downloads)](https://packagist.org/packages/ixnode/php-branch-diagram-builder)
[![PHPStan](https://img.shields.io/badge/PHPStan-Level%208-brightgreen.svg?style=flat)](https://phpstan.org/user-guide/rule-levels)
[![PHPStan](https://img.shields.io/badge/PHPCS-PSR12-brightgreen.svg?style=flat)](https://www.php-fig.org/psr/psr-12/)
[![LICENSE](https://img.shields.io/badge/License-MIT-428f7e.svg?logo=open%20source%20initiative&logoColor=white&labelColor=555555&style=flat)](https://github.com/ixnode/php-vault/blob/master/LICENSE)

A library with which branching strategies can be made visible as an image through a config file and included in
documentation files such as README.md.

## Installation

```bash
$ composer require ixnode/php-branch-diagram-builder --dev
```

## Usage

Create a file called `.pbdb.yml` with the following content:

```yaml
title: Trunk Based Development
width: 1500
branches:
  - name: 'main'
    system: 'Productive system'
    color-light: '#0151ad'
    color-dark: '#024796'
  - name: 'development'
    system: 'Development system'
    color-light: '#01aaad'
    color-dark: '#029496'
  - name: ['feature', 1]
    system: 'Local development'
    color-light: '#70b964'
    color-dark: '#46733f'
  - name: ['feature', 2]
    system: 'Local development'
    color-light: '#f9a61b'
    color-dark: '#c48416'
  - name: ['feature', 3]
    system: 'Local development'
    color-light: '#ed1164'
    color-dark: '#b30c4c'
steps:
  - type: 'init'
    source: null
    target: 'main'
  - type: 'checkout'
    source: 'main'
    target: 'development'
  - type: 'checkout'
    source: 'development'
    target: ['feature', 1]
  - type: 'commit'
    source: ['feature', 1]
  - type: 'checkout'
    source: 'development'
    target: ['feature', 2]
  - type: 'commit'
    source: ['feature', 2]
  - type: 'merge'
    source: ['feature', 1]
    target: 'development'
  - type: 'checkout'
    source: 'development'
    target: 'main'
  - type: 'merge'
    source: 'development'
    target: ['feature', 2]
  - type: 'commit'
    source: ['feature', 2]
  - type: 'merge'
    source: ['feature', 2]
    target: 'development'
  - type: 'merge'
    source: 'development'
    target: 'main'
  - type: 'checkout'
    source: 'development'
    target: ['feature', 3]
  - type: 'commit'
    source: ['feature', 3]
  - type: 'merge'
    source: ['feature', 3]
    target: 'development'
  - type: 'merge'
    source: 'development'
    target: 'main'
```

Execute the following command:

```bash
$ vendor/bin/pbdb-builder build .pbdb.yml
```

It creates the following image:

![Branching Strategy](.pbdb.png)

This can be easily added to you README.md file:

```text
![Branching Strategy](.phdb.png)
```

## Development

### Clone the app:

```bash
❯ git clone git@github.com:ixnode/php-branch-diagram-builder.git && \
  cd php-branch-diagram-builder
```

### Composer install

```bash
❯ php -v
PHP 8.0.11 (cli) (built: Sep 23 2021 22:03:11) ( NTS )
Copyright (c) The PHP Group
Zend Engine v4.0.11, Copyright (c) Zend Technologies
    with Zend OPcache v8.0.11, Copyright (c), by Zend Technologies
❯ composer -V
Composer version 2.1.9 2021-10-05 09:47:38
❯ composer install
...
```

### Run tests

```bash
❯ composer test
```

<details>
<summary>Result</summary>

```bash
> phpunit tests --testdox
PHPUnit 9.5.10 by Sebastian Bergmann and contributors.

Branch (Ixnode\PHPBranchDiagramBuilder\Tests\Branch)
 ✔ Branch
 ✔ Branch name
 ✔ Branch title

Step (Ixnode\PHPBranchDiagramBuilder\Tests\Step)
 ✔ 1) Test StepTest class (unknown: NULL -> master).
 ✔ 2) Test StepTest class (init: master -> master).
 ✔ 3) Test StepTest class (init: NULL -> master).
 ✔ 4) Test StepTest class (checkout: NULL -> master).
 ✔ 5) Test StepTest class (checkout: master -> NULL).
 ✔ 6) Test StepTest class (checkout: master -> master).
 ✔ 7) Test StepTest class (checkout: master -> develop).
 ✔ 8) Test StepTest class (commit: NULL -> master).
 ✔ 9) Test StepTest class (commit: master -> NULL).
 ✔ 10) Test StepTest class (commit: master -> master).
 ✔ 11) Test StepTest class (commit: master -> develop).
 ✔ 12) Test StepTest class (merge: NULL -> master).
 ✔ 13) Test StepTest class (merge: develop -> NULL).
 ✔ 14) Test StepTest class (merge: develop -> develop).
 ✔ 15) Test StepTest class (merge: develop -> master).

Time: 00:00.015, Memory: 6.00 MB

OK (18 tests, 35 assertions)
> phpstan analyse --level 8 --memory-limit 256M src tests
 24/24 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%

 [OK] No errors

> vendor/bin/php-cs-fixer --dry-run --using-cache=no --rules=@PSR12 fix src
Loaded config default.

Checked all files in 0.262 seconds, 14.000 MB memory used
> vendor/bin/php-cs-fixer --dry-run --using-cache=no --rules=@PSR12 fix tests
Loaded config default.

Checked all files in 0.035 seconds, 12.000 MB memory used
```

</details>

### Check the current version of the app:

```bash
# Git version
❯ git describe
v1.0.1

# VERSION file version
❯ cat VERSION
1.0.1
```

### Change version

Increase the version number according to your needs: `<MAJOR>`.`<MINOR>`.`<PATCH>`

| Name  | Description                                                                               | Command Parameter |
|-------|-------------------------------------------------------------------------------------------|-------------------|
| MAJOR | is increased when API incompatible changes are released.                                  | `--major 1`       |
| MINOR | is increased when new functionality that is compatible with the previous API is released. | `--minor 1`       |
| PATCH | is increased when changes include API-compatible bug fixes only.                          | `--patch 1`       |

* @see: https://semver.org/lang/de/

### Increase the version

```bash
# Show version
❯ cat VERSION
1.0.2

# Push changed VERSION file
❯ git add VERSION
❯ git commit -m "Add version $(cat VERSION)"
❯ git push
```

### Tag the app (git)

```bash
# Tag and push new git tag
❯ git tag -a "v$(cat VERSION)" -m "version v$(cat VERSION)"
❯ git push origin "v$(cat VERSION)"
```

### packagist.org

If you have connected your repository to packagist.org, check the result:

* https://packagist.org/

## A. Authors

* Björn Hempel <bjoern@hempel.li> - _Initial work_ - [https://github.com/bjoern-hempel](https://github.com/bjoern-hempel)

## B. License

This tutorial is licensed under the MIT License - see the [LICENSE.md](/LICENSE.md) file for details

## C. Closing words

Have fun! :)
