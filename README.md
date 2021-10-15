# PHPBranchDiagramBuilder

[![PHP](https://img.shields.io/badge/PHP-^7.4%20||%20^8.0-777bb3.svg?logo=php&logoColor=white&labelColor=555555&style=flat)](https://www.php.net/supported-versions.php)
[![Latest Stable Version](http://poser.pugx.org/ixnode/php-branch-diagram-builder/v)](https://packagist.org/packages/ixnode/php-branch-diagram-builder)
[![Total Downloads](http://poser.pugx.org/ixnode/php-branch-diagram-builder/downloads)](https://packagist.org/packages/ixnode/php-branch-diagram-builder)
[![PHPStan](https://img.shields.io/badge/PHPStan-Level%208-brightgreen.svg?style=flat)](https://phpstan.org/user-guide/rule-levels)
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

## A. Authors

* Björn Hempel <bjoern@hempel.li> - _Initial work_ - [https://github.com/bjoern-hempel](https://github.com/bjoern-hempel)

## B. License

This tutorial is licensed under the MIT License - see the [LICENSE.md](/LICENSE.md) file for details

## C. Closing words

Have fun! :)
