<?php

declare(strict_types=1);

namespace Queryr\WebApi\Endpoints;

use Queryr\WebApi\LinkHeaderBuilder;
use Queryr\WebApi\UseCases\ListItems\ItemListingRequest;
use Queryr\WebApi\UseCases\ListProperties\PropertyListingRequest;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GetItemsEndpoint {
	use EndpointConstructor;

	public function getResult( Request $request ) {
		$listingRequest = new ItemListingRequest();
		// TODO: strict validation of arguments
		$listingRequest->setPerPage( (int)$request->get( 'per_page', 100 ) );
		$listingRequest->setPage( (int)$request->get( 'page', 1 ) );

		$items = $this->apiFactory->newListItemsUseCase()->listItems( $listingRequest );

		return $this->app->json( $this->apiFactory->newItemListSerializer()->serialize( $items ) );
	}

}
