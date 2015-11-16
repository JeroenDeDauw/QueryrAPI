<?php

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

/**
 * @var \Silex\Application $app
 */
$app = require __DIR__ . '/../app/bootstrap.php';

$app->get(
	'/',
	function() use ($app) {
		$urlBuilder = $app['url_builder'];

		$api = [
			'items_url' => $urlBuilder->getApiPath( 'items{/item_id}' ),
			'item_types_url' => $urlBuilder->getApiPath( 'items/types' ),
			'properties_url' => $urlBuilder->getApiPath( 'properties{/property_id}' )
		];

		return $app->json( $api );
	}
);

$app->run();

return $app;