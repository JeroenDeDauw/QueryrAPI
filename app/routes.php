<?php

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

declare(strict_types=1);

use Queryr\WebApi\Endpoints\GetItemsEndpoint;
use Queryr\WebApi\Endpoints\GetPropertiesEndpoint;
use Queryr\WebApi\NoNullableReturnTypesException;
use Queryr\WebApi\UseCases\GetItem\GetItemRequest;
use Queryr\WebApi\UseCases\GetProperty\GetPropertyRequest;
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
			'properties_url' => $request->getUriForPath( '/properties{/property_id}' )
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
		$listingRequest = new GetItemRequest( $id );

		try {
			$item = $apiFactory->newGetItemUseCase()->getItem( $listingRequest );
			$json = $apiFactory->newSimpleItemSerializer()->serialize( $item );
			return $app->json( $json, 200 );
		}
		catch ( NoNullableReturnTypesException $ex ) {
			return $app->json( [
				'message' => 'Not Found',
				'code' => 404,
			], 404 );
		}
	}
)->assert( 'id', '(Q|q)[1-9]\d*' );

$app->get(
	'properties/{id}',
	function( Application $app, string $id ) use ( $apiFactory ) {
		$listingRequest = new GetPropertyRequest( $id );

		try {
			$property = $apiFactory->newGetPropertyUseCase()->getProperty( $listingRequest );
			$json = $apiFactory->newSimplePropertySerializer()->serialize( $property );
			return $app->json( $json, 200 );
		}
		catch ( NoNullableReturnTypesException $ex ) {
			return $app->json( [
				'message' => 'Not Found',
				'code' => 404,
			], 404 );
		}
	}
)->assert( 'id', '(P|p)[1-9]\d*' );

return $app;