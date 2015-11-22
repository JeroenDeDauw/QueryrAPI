<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Wikibase\DataFixtures\Items\Berlin;
use Wikibase\DataFixtures\Items\Germany;
use Wikibase\DataFixtures\Properties\CountryProperty;
use Wikibase\DataFixtures\Properties\PostalCodeProperty;

/**
 * @covers Queryr\WebApi\UseCases\GetItem\GetItemUseCase
 * @covers Queryr\WebApi\UseCases\GetItem\GetItemRequest
 * @covers Queryr\WebApi\Endpoints\GetItemEndpoint
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemEndpointTest extends ApiTestCase {

	public function testGivenNotKnownItemId_404isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/items/Q900404' );

		$this->assert404( $client->getResponse() );
	}

	public function testGivenKnownItemId_itemIsReturned() {
		$this->testEnvironment->insertItem( ( new Berlin() )->newItem() );

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
				]
			],
			$client->getResponse()
		);
	}

	public function testGivenLowercaseItemId_itemIsReturned() {
		$this->testEnvironment->insertItem( ( new Berlin() )->newItem() );

		$client = $this->createClient();

		$client->request( 'GET', '/items/q64' );

		$this->assertTrue( $client->getResponse()->isSuccessful(), 'request is successful' );
		$this->assertJson( $client->getResponse()->getContent(), 'response is json' );
	}

	public function testWhenDependenciesKnown_denormalizedItemIsReturned() {
		$this->testEnvironment->insertItem( ( new Berlin() )->newItem() );

		$this->testEnvironment->insertItem( ( new Germany() )->newItem() );
		$this->testEnvironment->insertProperty( ( new PostalCodeProperty() )->newProperty() );
		$this->testEnvironment->insertProperty( ( new CountryProperty() )->newProperty() );

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
					'country' => (object)[ // denormalized
						'value' => 'Germany', // denormalized
						'type' => 'string'
					],
					'P31' => (object)[
						'value' => 'Q515',
						'type' => 'string'
					],
					'postal code' => (object)[ // denormalized
						'value' => '10115–14199',
						'type' => 'string'
					]
				]
			],
			$client->getResponse()
		);
	}

	public function testGivenNonItemId_404isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/items/YouMadBro' );

		$this->assert404( $client->getResponse(), 'No route found for "GET /items/YouMadBro"' );
	}

}
