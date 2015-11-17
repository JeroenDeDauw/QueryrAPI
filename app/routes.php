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
 * @var \Queryr\WebApi\ApiFactory $apiFactory
 */

$app->get(
	'/',
	function() use ( $app, $apiFactory ) {
		$urlBuilder = $apiFactory->getUrlBuilder();

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
	function( Request $request ) use ( $app, $apiFactory ) {
		$listingRequest = new ItemListingRequest();
		// TODO: strict validation of per_page
		$listingRequest->setPerPage( (int)$request->get( 'per_page', 100 ) );

		$items = $apiFactory->newListItemsUseCase()->listItems( $listingRequest );

		return $app->json( $apiFactory->newItemListSerializer()->serialize( $items ) );
	}
);

$app->get(
	'properties',
		function( Request $request ) use ( $app, $apiFactory ) {
			$listingRequest = new PropertyListingRequest();
			// TODO: strict validation arguments
			$listingRequest->setPerPage( (int)$request->get( 'per_page', 100 ) );
			$listingRequest->setPage( (int)$request->get( 'page', 1 ) );

			$items = $apiFactory->newListPropertiesUseCase()->listProperties( $listingRequest );

			return $app->json( $apiFactory->newPropertyListSerializer()->serialize( $items ) );
		}
);

return $app;