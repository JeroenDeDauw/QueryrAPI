<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Wikibase\DataFixtures\Items\OnlineDatabase;
use Wikibase\DataFixtures\Items\Wikidata;
use Wikibase\DataFixtures\Items\WikimediaProject;
use Wikibase\DataFixtures\Properties\InstanceOfProperty;

/**
 * @covers Queryr\WebApi\Endpoints\GetItemPropertyDataEndpoint
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemPropertyDataEndpointTest extends ApiTestCase {

	public function testGivenNotKnownItemId_404isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/items/Q900404/data/P31' );

		$this->assert404( $client->getResponse() );
	}

	public function testGivenNonItemId_400isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/items/YouMadBro/data/P31' );

		$this->assert400( $client->getResponse(), 'Invalid id' );
	}

	public function testGivenNonPropertyId_400isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/items/Q1337/data/YouMadBro' );

		$this->assert400( $client->getResponse(), 'Invalid id' );
	}

	public function testGivenKnownItemId_itemPropertyDataIsReturned() {
		$this->testEnvironment->insertItem( ( new Wikidata() )->newItem() );
		$this->testEnvironment->insertItem( ( new WikimediaProject() )->newItem() );
		$this->testEnvironment->insertItem( ( new OnlineDatabase() )->newItem() );
		$this->testEnvironment->insertProperty( ( new InstanceOfProperty() )->newProperty() );

		$client = $this->createClient();

		$client->request( 'GET', '/items/Q2013/data/P31' );

		$this->assertSuccessResponse(
			(object)[
				'property' => (object)[
					'label' => 'instance of',
					'id' => 'P31',
					'url' => 'http://test.url/properties/P31',
				],
				'value' => (object)[
					'label' => 'Wikimedia project',
					'id' => 'Q14827288',
					'url' => 'http://test.url/items/Q14827288',
				],
				'type' => 'queryr-entity-identity',
				'values' => [
					(object)[
						'label' => 'Wikimedia project',
						'id' => 'Q14827288',
						'url' => 'http://test.url/items/Q14827288',
					],
					(object)[
						'label' => 'Q593744',
						'id' => 'Q593744',
						'url' => 'http://test.url/items/Q593744',
					],
					(object)[
						'label' => 'online database',
						'id' => 'Q7094076',
						'url' => 'http://test.url/items/Q7094076',
					],
				],
			],
			$client->getResponse()
		);
	}

}
