<?php

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

declare(strict_types=1);

use Queryr\WebApi\NoNullableReturnTypesException;
use Queryr\WebApi\UseCases\GetItem\GetItemRequest;
use Queryr\WebApi\UseCases\GetProperty\GetPropertyRequest;
use Queryr\WebApi\UseCases\ListItems\ItemListingRequest;
use Queryr\WebApi\UseCases\ListProperties\PropertyListingRequest;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * These varisbles need to be in scope when this file is included:
 *
 * @var \Silex\Application $app
 * @var \Queryr\WebApi\ApiFactory $apiFactory
 */

$app->get(
	'/',
	function() use ( $app, $apiFactory ) {
		$urlBuilder = $apiFactory->getUrlBuilder();

		$api = [
			'items_url' => $urlBuilder->getApiPath( 'items{/item_id}' ),
			'properties_url' => $urlBuilder->getApiPath( 'properties{/property_id}' )
		];

		return $app->json( $api );
	}
);

$app->get(
	'items',
	function( Request $request ) use ( $app, $apiFactory ) {
		$listingRequest = new ItemListingRequest();
		// TODO: strict validation of arguments
		$listingRequest->setPerPage( (int)$request->get( 'per_page', 100 ) );
		$listingRequest->setPage( (int)$request->get( 'page', 1 ) );

		$items = $apiFactory->newListItemsUseCase()->listItems( $listingRequest );

		return $app->json( $apiFactory->newItemListSerializer()->serialize( $items ) );
	}
);

$app->get(
	'properties',
	function( Request $request ) use ( $app, $apiFactory ) {
		$listingRequest = new PropertyListingRequest();
		// TODO: strict validation of arguments
		$listingRequest->setPerPage( (int)$request->get( 'per_page', 100 ) );
		$listingRequest->setPage( (int)$request->get( 'page', 1 ) );

		$items = $apiFactory->newListPropertiesUseCase()->listProperties( $listingRequest );

		return $app->json( $apiFactory->newPropertyListSerializer()->serialize( $items ) );
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
				'code' => 404,
				'message' => 'Not Found'
			], 404 );
		}
	}
);

$app->get(
	'properties/{id}',
	function( Application $app, string $id ) use ( $apiFactory ) {
		$listingRequest = new GetPropertyRequest( $id );

		try {
			$item = $apiFactory->newGetPropertyUseCase()->getProperty( $listingRequest );
			$json = $apiFactory->newSimplePropertySerializer()->serialize( $item );
			return $app->json( $json, 200 );
		}
		catch ( NoNullableReturnTypesException $ex ) {
			return $app->json( [
				'code' => 404,
				'message' => 'Not Found'
			], 404 );
		}
	}
);

return $app;