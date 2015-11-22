<?php

declare(strict_types=1);

namespace Queryr\WebApi\Endpoints;

use Queryr\Resources\PropertyList;
use Queryr\WebApi\LinkHeaderBuilder;
use Queryr\WebApi\PaginationHeaderSetter;
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

		$headerSetter = new PaginationHeaderSetter( $response->headers );
		$headerSetter->setHeaders(
			$request->getUriForPath( '/properties' ),
			$listingRequest,
			count( $properties->getElements() )
		);

		return $response;
	}

}