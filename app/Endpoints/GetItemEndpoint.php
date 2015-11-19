<?php

declare(strict_types=1);

namespace Queryr\WebApi\Endpoints;

use Queryr\WebApi\NoNullableReturnTypesException;
use Queryr\WebApi\UseCases\GetItem\GetItemRequest;
use Silex\Application;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GetItemEndpoint {
	use EndpointConstructor;

	public function getResult( string $id ) {
		$listingRequest = new GetItemRequest( $id );

		try {
			$item = $this->apiFactory->newGetItemUseCase()->getItem( $listingRequest );
			$json = $this->apiFactory->newSimpleItemSerializer()->serialize( $item );
			return $this->app->json( $json, 200 );
		}
		catch ( NoNullableReturnTypesException $ex ) {
			return $this->app->json( [
				'message' => 'Not Found',
				'code' => 404,
			], 404 );
		}
	}

}
