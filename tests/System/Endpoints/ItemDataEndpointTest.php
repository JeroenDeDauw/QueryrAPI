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
					'property' => (object)[
						'label' => 'P17',
						'id' => 'P17',
						'url' => 'http://test.url/properties/P17',
					],
					'value' => (object)[
						'label' => 'Q183',
						'id' => 'Q183',
						'url' => 'http://test.url/items/Q183',
					],
					'type' => 'queryr-entity-identity'
				],
				'P31' => (object)[
					'property' => (object)[
						'label' => 'P31',
						'id' => 'P31',
						'url' => 'http://test.url/properties/P31',
					],
					'value' => (object)[
						'label' => 'Q515',
						'id' => 'Q515',
						'url' => 'http://test.url/items/Q515',
					],
					'type' => 'queryr-entity-identity'
				],
				'P281' => (object)[
					'property' => (object)[
						'label' => 'P281',
						'id' => 'P281',
						'url' => 'http://test.url/properties/P281',
					],
					'value' => '10115–14199',
					'type' => 'string'
				]
			],
			$client->getResponse()
		);
	}

}
