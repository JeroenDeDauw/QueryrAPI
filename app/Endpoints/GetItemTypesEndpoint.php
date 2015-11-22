<?php

declare(strict_types=1);

namespace Queryr\WebApi\Endpoints;

use Queryr\WebApi\LinkHeaderBuilder;
use Queryr\WebApi\UseCases\ListItemTypes\ItemTypesListingRequest;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GetItemTypesEndpoint {
	use EndpointConstructor;

	public function getResult( Request $request ) {
		$listingRequest = new ItemTypesListingRequest();
		// TODO: strict validation of arguments
		$listingRequest->setPerPage( (int)$request->get( 'per_page', 100 ) );
		$listingRequest->setPage( (int)$request->get( 'page', 1 ) );

		$itemTypes = $this->apiFactory->newListItemTypesUseCase()->listItemTypes( $listingRequest );

		$response = $this->app->json( $this->getSerializedTypes( $itemTypes ) );

		$linkHeaderValues = $this->getLinkHeaderValues( $request, $listingRequest, $itemTypes );

		if ( $linkHeaderValues !== [] ) {
			$response->headers->set( 'Link', $linkHeaderValues );
		}

		return $response;
	}

	private function getSerializedTypes( array $types ) {
		$serializer = $this->apiFactory->getItemTypeSerializer();
		$serializedTypes = [];

		foreach ( $types as $typeId ) {
			$serializedTypes[] = $serializer->serialize( $typeId );
		}

		return $serializedTypes;
	}

	private function getLinkHeaderValues( Request $request, ItemTypesListingRequest $listingRequest,
			array $itemTypes ): array {

		$headerBuilder = new LinkHeaderBuilder();
		$linkHeaderValues = [];

		if ( count( $itemTypes ) === $listingRequest->getPerPage() ) {
			$linkHeaderValues[] = $headerBuilder->buildLinkHeader(
				'next',
				$request->getUriForPath( '/items/types' ),
				[
					'page' => $listingRequest->getPage() + 1,
					'per_page' => $listingRequest->getPerPage()
				]
			);
		}

		if ( $listingRequest->getPage() !== 1 ) {
			$linkHeaderValues[] = $headerBuilder->buildLinkHeader(
				'first',
				$request->getUriForPath( '/items/types' ),
				[
					'page' => 1,
					'per_page' => $listingRequest->getPerPage()
				]
			);
		}

		return $linkHeaderValues;
	}

}