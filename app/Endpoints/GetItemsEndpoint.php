<?php

declare(strict_types=1);

namespace Queryr\WebApi\Endpoints;

use Queryr\Resources\ItemList;
use Queryr\WebApi\LinkHeaderBuilder;
use Queryr\WebApi\UseCases\ListItems\ItemListingRequest;
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

		$response = $this->app->json( $this->apiFactory->newItemListSerializer()->serialize( $items ) );

		$linkHeaderValues = $this->getLinkHeaderValues( $request, $listingRequest, $items );

		if ( $linkHeaderValues !== [] ) {
			$response->headers->set( 'Link', $linkHeaderValues );
		}

		return $response;
	}

	private function getLinkHeaderValues( Request $request, ItemListingRequest $listingRequest, ItemList $items ): array {
		$headerBuilder = new LinkHeaderBuilder();
		$linkHeaderValues = [];

		if ( count( $items->getElements() ) === $listingRequest->getPerPage() ) {
			$linkHeaderValues[] = $headerBuilder->buildLinkHeader(
				'next',
				$request->getUriForPath( '/items' ),
				[
					'page' => $listingRequest->getPage() + 1,
					'per_page' => $listingRequest->getPerPage()
				]
			);
		}

		if ( $listingRequest->getPage() !== 1 ) {
			$linkHeaderValues[] = $headerBuilder->buildLinkHeader(
				'first',
				$request->getUriForPath( '/items' ),
				[
					'page' => 1,
					'per_page' => $listingRequest->getPerPage()
				]
			);
		}

		return $linkHeaderValues;
	}

}
