<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Wikibase\DataFixtures\Properties\PostalCodeProperty;

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

	public function testGivenNonPropertyId_404isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/properties/YouMadBro/label' );

		$this->assert404( $client->getResponse(), 'No route found for "GET /properties/YouMadBro/label"' );
	}

	public function testGivenKnownPropertyId_propertyLabelIsReturned() {
		$this->testEnvironment->insertProperty( ( new PostalCodeProperty() )->newProperty() );

		$client = $this->createClient();

		$client->request( 'GET', '/properties/P281/label' );

		$this->assertSuccessResponse(
			'postal code',
			$client->getResponse()
		);
	}

}
