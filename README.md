[![Build Status](https://travis-ci.org/JeroenDeDauw/QueryrAPI.svg)](https://travis-ci.org/JeroenDeDauw/QueryrAPI)

QueryR API is an application that provides a REST webservice for accessing [Wikibase]
(http://wikiba.se) data.

## System dependencies

* PHP 7
* php5-sqlite (only needed for running the tests)

## Installation

    composer install
    cp app/config/db-example.json app/config/db.json

The database schema can be initialized via the install command of the [Replicator CLI tool]
(https://github.com/JeroenDeDauw/Replicator).

## Running the API

For development

	cd web
	php -S localhost:8000

For production, see the [Silex documentation on webserver configuration](http://silex.sensiolabs.org/doc/web_servers.html).

## API documentation

This REST API is self documenting. Hit the root endpoint to get a list of available endpoints.

## Running the tests

For tests only

    composer test

For style checks only

	composer cs

For a full CI run

	composer ci

## Profiling

When accessing the API via `web/index.dev.php`, profiling information will be generated and in
`app/cache/profiler`. You can access the profiler UI via `index.dev.php/_profiler`.

## Internal structure

* `web/`: web accessible code
	* `index.php`: production entry point
* `app/`: contains configuration and all framework (Silex) dependent code
	* `bootstrap.php`: framework application bootstrap (used by System tests)
	* `routes.php`: defines the routes and their handlers
	* `Endpoints/`: some of the route handlers have a dedicated file here to not clutter `routes.php`
* `src/`: contains framework agnostic code
	* `ApiFactory.php`: top level factory and service locator (used by Integration tests)
	* `UseCases/`: one directory per use case
	* Dependencies on Symfony Components are explicitly defined in `composer.json`
* `tests/`: tests mirror the directory and namespace structure of the production code
	* `Unit/`: small isolated tests (cannot access app, db or framework)
	* `Integration/`: tests combining several units (cannot access framework)
	* `System/`: edge-to-edge tests
	* `TestEnvironment.php`: encapsulates application setup for integration and system tests
	* `Fixtures/`: test stubs and spies

## Release notes

### Version 0.2 (dev)

* Added top level elements to GET `/items/$id` default response format:
    * `label_url`
    * `description_url`
    * `aliases_url`
    * `wikidata_url`
	* `wikipedia_html_url`

* Added top level elements to GET `/properties/$id` default response format:
    * `label_url`
    * `description_url`
    * `aliases_url`
    * `wikidata_url`
	* `data`

### Version 0.1 (2015-11-30)

* Initial release