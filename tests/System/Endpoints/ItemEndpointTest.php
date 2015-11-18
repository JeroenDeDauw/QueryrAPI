<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Wikibase\DataFixtures\Items\Berlin;
use Wikibase\DataFixtures\Items\City;
use Wikibase\DataFixtures\Items\Germany;
use Wikibase\DataFixtures\Properties\PostalCodeProperty;

/**
 * @covers Queryr\WebApi\UseCases\GetItem\GetItemUseCase
 * @covers Queryr\WebApi\UseCases\GetItem\GetItemRequest
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemEndpointTest extends ApiTestCase {

	public function testItemsEndpointReturnsEmptyJsonArray() {
		$client = $this->createClient();

		$client->request( 'GET', '/items' );

		$this->assertSuccessResponse( [], $client->getResponse() );
	}

	private function storeThreeItems() {
		$this->testEnvironment->insertItem( ( new Germany() )->newItem() );
		$this->testEnvironment->insertItem( ( new Berlin() )->newItem() );
		$this->testEnvironment->insertItem( ( new City() )->newItem() );
	}

	public function testGivenNotKnownItemId_404isReturned() {
		$this->storeThreeItems();
		$client = $this->createClient();

		$client->request( 'GET', '/items/Q900404' );

		$this->assert404( $client->getResponse() );
	}

	public function testGivenKnownItemId_itemIsReturned() {
		$this->storeThreeItems();
		$client = $this->createClient();

		$client->request( 'GET', '/items/Q64' );

		$this->assertSuccessResponse(
			(object)[
				'id' => (object)[
					'wikidata' => 'Q64',
					'en_wikipedia' => 'Berlin'
				],
				'label' => 'Berlin',
				'description' => 'capital city and state of Germany',
				'data' => (object)[
					'P17' => (object)[
						'value' => 'Q183', // TODO: denormalize
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
				]
			],
			$client->getResponse()
		);
	}

//	public function testGivenKnownItemIdWhenDependenciesAlsoKnown_denormalizedItemIsReturned() {
//		$this->storeThreeItems();
//		$this->testEnvironment->insertProperty( ( new PostalCodeProperty() )->newProperty() );
//
//		$client = $this->createClient();
//
//		$client->request( 'GET', '/items/Q64' );
//
//		$this->assertSuccessResponse(
//			(object)[
//				'id' => (object)[
//					'wikidata' => 'Q64',
//					'en_wikipedia' => 'Berlin'
//				],
//				'label' => 'Berlin',
//				'description' => 'capital city and state of Germany',
//				'data' => (object)[
//					'P17' => (object)[ // TODO: denormalize
//						'value' => 'Q183', // TODO: denormalize
//						'type' => 'string'
//					],
//					'P31' => (object)[ // TODO: denormalize
//						'value' => 'Q515',
//						'type' => 'string'
//					],
//					'postal code' => (object)[
//						'value' => '10115–14199',
//						'type' => 'string'
//					]
//				]
//			],
//			$client->getResponse()
//		);
//	}

}
