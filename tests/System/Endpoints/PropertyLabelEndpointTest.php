<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyLabelEndpointTest extends ApiTestCase {

	public function testGivenNotKnownPropertyId_404isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/properties/P900404/label' );

		$this->assert404( $client->getResponse() );
	}

	public function testGivenNonPropertyId_400isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/properties/YouMadBro/label' );

		$this->assert400( $client->getResponse(), 'Invalid id' );
	}

	public function testGivenKnownPropertyId_propertyLabelIsReturned() {
		$property = new Property( new PropertyId( 'P1337' ), null, 'string' );
		$property->getFingerprint()->setLabel( 'en', 'postal code' );

		$this->testEnvironment->insertProperty( $property );

		$client = $this->createClient();

		$client->request( 'GET', '/properties/P1337/label' );

		$this->assertSuccessResponse(
			'postal code',
			$client->getResponse()
		);
	}

}
