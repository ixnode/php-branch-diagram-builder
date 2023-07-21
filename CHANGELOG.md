# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## Releases

### [1.2.1] - 2023-07-20

* Composer update

### [1.2.0] - 2022-01-05

* [#2](https://github.com/ixnode/php-vault/issues/2) - Add FUNDING.yml

### [1.1.0] - 2022-01-05

* [#1](https://github.com/ixnode/php-vault/issues/1) - Add Semantic Versioning 2.0.0

### [v1.0.2] - 2021-10-16

* Add PSR-12 Standard

### [v1.0.1] - 2021-10-16

* Release v1.0.1

### [v1.0.0] - 2021-06-30

* Build branching diagrams from given yaml file

## Add new version

```bash
# checkout master branch
$ git checkout master && git pull

# add new version
$ echo "v1.1.0" > VERSION

# Change changelog
$ vi CHANGELOG.md

# Push new version
$ git add CHANGELOG.md VERSION && git commit -m "Add version $(cat VERSION)" && git push

# Tag and push new version
$ git tag -a "$(cat VERSION)" -m "Version $(cat VERSION)" && git push origin "$(cat VERSION)"
```
