<?php

declare(strict_types=1);

namespace Queryr\WebApi\Endpoints;

use Queryr\Resources\PropertyList;
use Queryr\WebApi\LinkHeaderBuilder;
use Queryr\WebApi\UseCases\ListProperties\PropertyListingRequest;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GetPropertiesEndpoint {
	use EndpointConstructor;

	public function getResult( Request $request ) {
		$listingRequest = new PropertyListingRequest();
		// TODO: strict validation of arguments
		$listingRequest->setPerPage( (int)$request->get( 'per_page', 100 ) );
		$listingRequest->setPage( (int)$request->get( 'page', 1 ) );

		$properties = $this->apiFactory->newListPropertiesUseCase()->listProperties( $listingRequest );

		$response = $this->app->json( $this->apiFactory->newPropertyListSerializer()->serialize( $properties ) );

		$linkHeaderValues = $this->getLinkHeaderValues( $request, $listingRequest, $properties );

		if ( $linkHeaderValues !== [] ) {
			$response->headers->set( 'Link', $linkHeaderValues );
		}

		return $response;
	}

	private function getLinkHeaderValues( Request $request, PropertyListingRequest $listingRequest,
			PropertyList $properties ): array {

		$headerBuilder = new LinkHeaderBuilder();
		$linkHeaderValues = [];

		if ( count( $properties->getElements() ) === $listingRequest->getPerPage() ) {
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

		return $linkHeaderValues;
	}

}