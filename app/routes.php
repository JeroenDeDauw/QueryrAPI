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

		$properties = $apiFactory->newListPropertiesUseCase()->listProperties( $listingRequest );

		$response = $app->json( $apiFactory->newPropertyListSerializer()->serialize( $properties ) );

		$headerBuilder = new \Queryr\WebApi\LinkHeaderBuilder();
		$linkHeaderValues = [];

		if ( $properties->getElements() == $listingRequest->getPerPage() ) {
			$linkHeaderValues[] = $headerBuilder->buildLinkHeader(
				'next',
				$request->getUriForPath( '/properties' ),
				[
					'page' => $listingRequest->getPage() + 1,
					'per_page' => $listingRequest->getPerPage()
				]
			);
		}

		if ( $listingRequest->getPage() !== 1 ) {
			$linkHeaderValues[] = $headerBuilder->buildLinkHeader(
				'first',
				$request->getUriForPath( '/properties' ),
				[
					'page' => 1,
					'per_page' => $listingRequest->getPerPage()
				]
			);
		}

		if ( $linkHeaderValues !== [] ) {
			$response->headers->set( 'Link', $linkHeaderValues );
		}

		return $response;
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