<?php

/**
 * @var \Silex\Application $app
 */
$app = require_once __DIR__ . '/../app/bootstrap.php';

$app->get(
	'/',
	function() use ($app) {
		$urlBuilder = new \Queryr\WebApi\UrlBuilder(
			array_key_exists( 'HTTP_HOST', $_SERVER ) ? 'http://' . $_SERVER['HTTP_HOST'] : 'testurl'
		);

		$api = [
			'items_url' => $urlBuilder->getApiPath( 'items{/item_id}' ),
			'item_types_url' => $urlBuilder->getApiPath( 'items/types' ),
			'properties_url' => $urlBuilder->getApiPath( 'properties{/property_id}' )
		];

		return $app->json( $api );
	}
);

// TODO: disable
$app['debug'] = true;

$app->run();