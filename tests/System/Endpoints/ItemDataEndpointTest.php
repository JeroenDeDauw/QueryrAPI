<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Wikibase\DataFixtures\Items\Berlin;

/**
 * @covers Queryr\WebApi\Endpoints\GetItemDataEndpoint
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemDataEndpointTest extends ApiTestCase {

	public function testGivenNotKnownItemId_404isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/items/Q900404/data' );

		$this->assert404( $client->getResponse() );
	}

	public function testGivenNonItemId_404isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/items/YouMadBro/data' );

		$this->assert404( $client->getResponse(), 'No route found for "GET /items/YouMadBro/data"' );
	}

	public function testGivenKnownItemId_itemDataIsReturned() {
		$this->testEnvironment->insertItem( ( new Berlin() )->newItem() );

		$client = $this->createClient();

		$client->request( 'GET', '/items/Q64/data' );

		$this->assertSuccessResponse(
			(object)[
				'P17' => (object)[
					'value' => 'Q183',
					'type' => 'string'
				],
				'P31' => (object)[
					'value' => 'Q515',
					'type' => 'string'
				],
				'P281' => (object)[
					'value' => '10115–14199',
					'type' => 'string'
				]
			],
			$client->getResponse()
		);
	}

}
