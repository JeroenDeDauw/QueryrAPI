<?php

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

declare(strict_types=1);

use Queryr\WebApi\UseCases\ListItems\ItemListingRequest;
use Queryr\WebApi\UseCases\ListProperties\PropertyListingRequest;
use Symfony\Component\HttpFoundation\Request;

/**
 * These varisbles need to be in scope when this file is included:
 *
 * @var \Silex\Application $app
 * @var \Queryr\WebApi\ApiServices $services
 */

$app->get(
	'/',
	function() use ( $app, $services ) {
		$urlBuilder = $services->getUrlBuilder();

		$api = [
			'items_url' => $urlBuilder->getApiPath( 'items{/item_id}' ),
			'item_types_url' => $urlBuilder->getApiPath( 'items/types' ),
			'properties_url' => $urlBuilder->getApiPath( 'properties{/property_id}' )
		];

		return $app->json( $api );
	}
);

$app->get(
	'items',
	function( Request $request ) use ( $app, $services ) {
		$listingRequest = new ItemListingRequest();
		// TODO: strict validation of per_page
		$listingRequest->setPerPage( (int)$request->get( 'per_page', 100 ) );

		$items = $services->newListItemsUseCase()->listItems( $listingRequest );

		return $app->json( $services->newItemListSerializer()->serialize( $items ) );
	}
);

$app->get(
	'items/types',
	function() use ( $app ) {
		return $app->json( [] );
	}
);

$app->get(
	'properties',
		function( Request $request ) use ( $app, $services ) {
			$listingRequest = new PropertyListingRequest();
			// TODO: strict validation of per_page
			$listingRequest->setPerPage( (int)$request->get( 'per_page', 100 ) );

			$items = $services->newListPropertiesUseCase()->listProperties( $listingRequest );

			return $app->json( $services->newPropertyListSerializer()->serialize( $items ) );
		}
);

return $app;