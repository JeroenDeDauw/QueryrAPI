<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use DataValues\StringValue;
use Wikibase\DataFixtures\Properties\CountryProperty;
use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\Snak\PropertyValueSnak;
use Wikibase\DataModel\Term\Fingerprint;

/**
 * @covers Queryr\WebApi\UseCases\GetProperty\GetPropertyUseCase
 * @covers Queryr\WebApi\UseCases\GetProperty\GetPropertyRequest
 * @covers Queryr\WebApi\Endpoints\GetPropertyEndpoint
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
		$fingerprint = new Fingerprint();
		$fingerprint->setLabel( 'en', 'country' );
		$fingerprint->setLabel( 'nl', 'land' );
		$fingerprint->setDescription( 'en', 'sovereign state of this item' );

		$countryProperty = new Property( new PropertyId( 'P17' ), $fingerprint, 'wikibase-item' );

		$countryProperty->getStatements()->addNewStatement(
			new PropertyValueSnak( 42, new StringValue( 'foobar' ) )
		);

		$this->testEnvironment->insertProperty( $countryProperty );

		$client = $this->createClient();

		$client->request( 'GET', '/properties/P17' );

		$this->assertSuccessResponse(
			(object)[
				'type' => 'wikibase-item',

				'id' => (object)[
					'wikidata' => 'P17'
				],
				'label' => 'country',
				'description' => 'sovereign state of this item',
				'label_url' => 'http://test.url/properties/P17/label',
				'description_url' => 'http://test.url/properties/P17/description',
				'aliases_url' => 'http://test.url/properties/P17/aliases',
				'wikidata_url' => 'https://www.wikidata.org/entity/P17',
				'data_url' => 'http://test.url/properties/P17/data',
				'data' => [
					'P42' => [
						'value' => 'foobar',
						'type' => 'string'
					]
				],
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
