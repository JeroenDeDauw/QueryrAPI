<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyDescriptionEndpointTest extends ApiTestCase {

	public function testGivenNotKnownPropertyId_404isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/properties/P900404/description' );

		$this->assert404( $client->getResponse() );
	}

	public function testGivenNonPropertyId_400isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/properties/YouMadBro/description' );

		$this->assert400( $client->getResponse(), 'Invalid id' );
	}

	public function testGivenKnownPropertyId_propertyDescriptionIsReturned() {
		$property = new Property( new PropertyId( 'P1337' ), null, 'string' );
		$property->getFingerprint()->setDescription( 'en', 'foo bar baz' );

		$this->testEnvironment->insertProperty( $property );

		$client = $this->createClient();

		$client->request( 'GET', '/properties/P1337/description' );

		$this->assertSuccessResponse(
			'foo bar baz',
			$client->getResponse()
		);
	}

}
