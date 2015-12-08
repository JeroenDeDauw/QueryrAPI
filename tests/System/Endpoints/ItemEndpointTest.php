<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use DataValues\StringValue;
use Wikibase\DataFixtures\Items\Berlin;
use Wikibase\DataFixtures\Items\Germany;
use Wikibase\DataFixtures\Properties\CountryProperty;
use Wikibase\DataFixtures\Properties\PostalCodeProperty;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\Snak\PropertyValueSnak;

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
				'label_url' => 'http://test.url/items/Q64/label',
				'description_url' => 'http://test.url/items/Q64/description',
				'aliases_url' => 'http://test.url/items/Q64/aliases',
				'wikidata_url' => 'https://www.wikidata.org/entity/Q64',
				'data_url' => 'http://test.url/items/Q64/data',
				'data' => (object)[
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
				'wikipedia_html_url' => 'https://www.wikidata.org/wiki/Special:GoToLinkedPage/enwiki/Q64'
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
				'label_url' => 'http://test.url/items/Q64/label',
				'description_url' => 'http://test.url/items/Q64/description',
				'aliases_url' => 'http://test.url/items/Q64/aliases',
				'wikidata_url' => 'https://www.wikidata.org/entity/Q64',
				'data_url' => 'http://test.url/items/Q64/data',
				'data' => (object)[
					'P17' => (object)[
						'property' => (object)[
							'label' => 'country', // denormalized
							'id' => 'P17',
							'url' => 'http://test.url/properties/P17',
						],
						'value' => (object)[
							'label' => 'Germany', // denormalized
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
							'label' => 'postal code', // denormalized
							'id' => 'P281',
							'url' => 'http://test.url/properties/P281',
						],
						'value' => '10115–14199',
						'type' => 'string'
					]
				],
				'wikipedia_html_url' => 'https://www.wikidata.org/wiki/Special:GoToLinkedPage/enwiki/Q64'
			],
			$client->getResponse()
		);
	}

	public function testGivenNonItemId_404isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/items/YouMadBro' );

		$this->assert404( $client->getResponse(), 'No route found for "GET /items/YouMadBro"' );
	}

	/**
	 * @depends testGivenKnownItemId_itemIsReturned
	 */
	public function testDataIsOrderedByStatementId() {
		$item = new Item( new ItemId( 'Q1' ) );

		$item->getStatements()->addNewStatement(
			new PropertyValueSnak( new PropertyId( 'P1337' ), new StringValue( 'kittens' ) )
		);

		$item->getStatements()->addNewStatement(
			new PropertyValueSnak( new PropertyId( 'P23' ), new StringValue( 'cats' ) )
		);

		$item->getStatements()->addNewStatement(
			new PropertyValueSnak( new PropertyId( 'P42' ), new StringValue( 'bunnies' ) )
		);

		$this->testEnvironment->insertItem( $item );

		$client = $this->createClient();

		$client->request( 'GET', '/items/Q1' );

		$this->assertSame(
			[ 'P23', 'P42', 'P1337' ],
			array_keys( json_decode( $client->getResponse()->getContent(), true )['data'] )
		);
	}

}
