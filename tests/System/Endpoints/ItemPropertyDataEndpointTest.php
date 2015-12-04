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

	public function testGivenNonItemId_404isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/items/YouMadBro/data/P31' );

		$this->assert404( $client->getResponse(), 'No route found for "GET /items/YouMadBro/data/P31"' );
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
				'value' => 'Wikimedia project',
				'type' => 'string',
				'values' => [
					'Wikimedia project',
					'Q593744',
					'online database',
				],
			],
			$client->getResponse()
		);
	}

}
