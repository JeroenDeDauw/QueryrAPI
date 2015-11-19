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

## API documentation

This REST API is self documenting. Hit the root endpoint to get a list of available endpoints.

## Running the tests

For tests only

    composer test

For style checks only

	composer cs

For a full CI run

	composer ci

## Internal structure

* `web/`: web accessible code
	* `index.php`: production entry point
* `app/`: contains configuration and all framework dependent code
	* `bootstrap.php`: framework application bootstrap (used by System tests)
* `src/`: contains framework agnostic code
	* `ApiFactory.php`: top level factory and service locator (used by Integration tests)
	* `UseCases/`: one directory per use case

## Release notes

### Version 0.1 (dev)

*