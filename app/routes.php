<?php

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

declare(strict_types=1);

use Queryr\WebApi\ApiServices;
use Queryr\WebApi\UseCases\ListItems\ItemListingRequest;
use Symfony\Component\HttpFoundation\Request;

/**
 * @var \Silex\Application $app
 */

$services = new ApiServices( $app );

$app->get(
	'/',
	function() use ( $app ) {
		$urlBuilder = $app['url_builder'];

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
	function() use ( $app ) {
		return $app->json( [] );
	}
);

return $app;