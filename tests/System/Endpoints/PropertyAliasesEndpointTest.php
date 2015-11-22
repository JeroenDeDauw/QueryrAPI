<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyAliasesEndpointTest extends ApiTestCase {

	// https://github.com/JeroenDeDauw/QueryrAPI/issues/14
//	public function testGivenNotKnownPropertyId_404isReturned() {
//		$client = $this->createClient();
//
//		$client->request( 'GET', '/properties/P900404/aliases' );
//
//		$this->assert404( $client->getResponse() );
//	}

	public function testGivenNonPropertyId_404isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/properties/YouMadBro/aliases' );

		$this->assert404( $client->getResponse(), 'No route found for "GET /properties/YouMadBro/aliases"' );
	}

	public function testGivenKnownPropertyId_propertyLabelIsReturned() {
		$property = new Property( new PropertyId( 'P1337' ), null, 'string' );
		$property->getFingerprint()->setAliasGroup( 'en', [ 'foo', 'bar', 'baz' ] );

		$this->testEnvironment->insertProperty( $property );

		$client = $this->createClient();

		$client->request( 'GET', '/properties/P1337/aliases' );

		$this->assertSuccessResponse(
			[ 'foo', 'bar', 'baz' ],
			$client->getResponse()
		);
	}

}
