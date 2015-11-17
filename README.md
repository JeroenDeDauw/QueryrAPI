[![Build Status](https://travis-ci.org/JeroenDeDauw/QueryrAPI.svg)](https://travis-ci.org/JeroenDeDauw/QueryrAPI)

## System dependencies

* PHP 7
* php5-sqlite (only needed for running the tests)

## Installation

    composer install
    cp app/config/db-example.json app/config/db.json

## Running the API

	cd web
	php -S localhost:8000

## Running the tests

For tests only

    composer test

For style checks only

	composer cs

For a full CI run

	composer ci

## Release notes

### Version 0.1 (dev)

*