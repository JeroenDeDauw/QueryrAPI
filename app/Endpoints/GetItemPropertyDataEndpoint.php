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
class GetItemPropertyDataEndpoint {
	use EndpointConstructor;

	public function getResult( string $itemId, string $propertyLabel ) {
		$listingRequest = new GetItemRequest( $itemId );

		try {
			$item = $this->apiFactory->newGetItemUseCase()->getItem( $listingRequest );
			$json = $this->apiFactory->newSimpleItemSerializer()->serialize( $item );
			$data = array_key_exists( $propertyLabel, $json['data'] ) ? $json['data'][$propertyLabel] : [];
			return $this->app->json( $data, 200 );
		}
		catch ( NoNullableReturnTypesException $ex ) {
			return $this->app->json( [
				'message' => 'Not Found',
				'code' => 404,
			], 404 );
		}
	}

}
