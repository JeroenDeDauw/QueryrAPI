<?php

declare(strict_types=1);

namespace Queryr\WebApi\Endpoints;

use OhMyPhp\NoNullableReturnTypesException;
use Queryr\WebApi\ResponseModel\SimpleStatement;
use Queryr\WebApi\UseCases\GetProperty\GetPropertyRequest;
use Silex\Application;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GetPropertyEndpoint {
	use EndpointConstructor;

	public function getResult( string $id ) {
		$listingRequest = new GetPropertyRequest( $id );

		try {
			$property = $this->apiFactory->newGetPropertyUseCase()->getProperty( $listingRequest );

			usort( $property->statements, function( SimpleStatement $s0, SimpleStatement $s1 ) {
				return $s0->propertyId->getNumericId() <=> $s1->propertyId->getNumericId();
			} );

			$json = $this->apiFactory->newSimplePropertySerializer()->serialize( $property );
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
