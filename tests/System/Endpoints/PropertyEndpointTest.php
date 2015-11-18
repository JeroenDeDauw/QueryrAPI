<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Wikibase\DataFixtures\Properties\CountryProperty;

/**
 * @covers Queryr\WebApi\UseCases\GetProperty\GetPropertyUseCase
 * @covers Queryr\WebApi\UseCases\GetProperty\GetPropertyRequest
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyEndpointTest extends ApiTestCase {

	public function testGivenNotKnownPropertyId_404isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/properties/P900404' );

		$this->assert404( $client->getResponse() );
	}

	public function testGivenKnownPropertyId_propertyIsReturned() {
		$this->testEnvironment->insertProperty( ( new CountryProperty() )->newProperty() );

		$client = $this->createClient();

		$client->request( 'GET', '/properties/P17' );

		$this->assertSuccessResponse(
			(object)[
				'id' => (object)[
					'wikidata' => 'P17'
				],
				'label' => 'country',
				'description' => 'sovereign state of this item',
				'type' => 'wikibase-item'
			],
			$client->getResponse()
		);
	}

	public function testGivenLowercasePropertyId_propertyIsReturned() {
		$this->testEnvironment->insertProperty( ( new CountryProperty() )->newProperty() );

		$client = $this->createClient();

		$client->request( 'GET', '/properties/p17' );

		$this->assertTrue( $client->getResponse()->isSuccessful(), 'request is successful' );
		$this->assertJson( $client->getResponse()->getContent(), 'response is json' );
	}

	public function testGivenNonPropertyId_404isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/properties/YouMadBro' );

		$this->assert404( $client->getResponse(), 'No route found for "GET /properties/YouMadBro"' );
	}

}
