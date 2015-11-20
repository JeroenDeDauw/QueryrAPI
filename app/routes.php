<?php

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

declare(strict_types=1);

use Queryr\WebApi\Endpoints\GetItemEndpoint;
use Queryr\WebApi\Endpoints\GetItemsEndpoint;
use Queryr\WebApi\Endpoints\GetPropertiesEndpoint;
use Queryr\WebApi\Endpoints\GetPropertyEndpoint;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * These variables need to be in scope when this file is included:
 *
 * @var \Silex\Application $app
 * @var \Queryr\WebApi\ApiFactory $apiFactory
 */

$app->get(
	'/',
	function( Request $request ) use ( $app ) {
		$api = [
			'items_url' => $request->getUriForPath( '/items{/item_id}' ),
			'properties_url' => $request->getUriForPath( '/properties{/property_id}' ),
			'item_types_url' => $request->getUriForPath( '/items/types' )
		];

		return $app->json( $api );
	}
);

$app->get(
	'items',
	function( Request $request ) use ( $app, $apiFactory ) {
		return ( new GetItemsEndpoint( $app, $apiFactory ) )->getResult( $request );
	}
);

$app->get(
	'properties',
	function( Request $request ) use ( $app, $apiFactory ) {
		return ( new GetPropertiesEndpoint( $app, $apiFactory ) )->getResult( $request );
	}
);

$app->get(
	'items/{id}',
	function( Application $app, string $id ) use ( $apiFactory ) {
		return ( new GetItemEndpoint( $app, $apiFactory ) )->getResult( $id );
	}
)->assert( 'id', '(Q|q)[1-9]\d*' );

$app->get(
	'properties/{id}',
	function( Application $app, string $id ) use ( $apiFactory ) {
		return ( new GetPropertyEndpoint( $app, $apiFactory ) )->getResult( $id );
	}
)->assert( 'id', '(P|p)[1-9]\d*' );

return $app;