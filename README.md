[![Build Status](https://travis-ci.org/JeroenDeDauw/QueryrAPI.svg)](https://travis-ci.org/JeroenDeDauw/QueryrAPI)

QueryR API is an application that provides a REST webservice for accessing [Wikibase]
(http://wikiba.se) data.

## System dependencies

* PHP >= 7
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

### Version 0.3 (dev)

* Elements in the data section of the item and property GET response are now ordered by property id
* Invalid item and property ids now cause a 400 response code instead of a 404 one

### Version 0.2 (2015-12-06)

* Added top level elements to GET `/items/$id` default response format:
    * `label_url` (required string)
    * `description_url` (required string)
    * `aliases_url` (required string)
    * `wikidata_url` (required string)
	* `wikipedia_html_url` (optional string)
* Added top level elements to GET `/properties/$id` default response format:
    * `label_url` (required string)
    * `description_url` (required string)
    * `aliases_url` (required string)
    * `wikidata_url` (required string)
	* `data` (required map)
* The `data` top level element in the item response format now indexes by property id rather than property label
* Added `property` (required map) to statement serialization
* Values of type `wikibase-entityid` now get turned into a map with `label`, `id` and `url` rather than into a string
* Changed GET `/items/$id/data/$property_label` to `/items/$id/data/$property_id`

### Version 0.1 (2015-11-30)

* Initial release