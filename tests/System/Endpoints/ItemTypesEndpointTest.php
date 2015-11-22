<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Wikibase\DataFixtures\Items\Berlin;
use Wikibase\DataFixtures\Items\City;
use Wikibase\DataFixtures\Items\Germany;
use Wikibase\DataFixtures\Items\State;
use Wikibase\DataFixtures\Items\Wikidata;
use Wikibase\DataFixtures\Items\WikimediaProject;
use Wikibase\DataFixtures\Properties\InstanceOfProperty;

/**
 * @covers Queryr\WebApi\UseCases\ListItemTypes\ListItemTypesUseCase
 * @covers Queryr\WebApi\UseCases\ListItemTypes\ItemTypesListingRequest
 * @covers Queryr\WebApi\Endpoints\GetItemTypesEndpoint
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ListItemTypesEndpointTest extends ApiTestCase {

	public function testItemTypesEndpointReturnsEmptyJsonArray() {
		$client = $this->createClient();

		$client->request( 'GET', '/items/types' );

		$this->assertSame( '[]', $client->getResponse()->getContent() );

		$this->assertTrue( $client->getResponse()->isSuccessful(), 'request is successful' );
		$this->assertJson( $client->getResponse()->getContent(), 'response is json' );
	}

	private function storeThreeInstanceOfReferencedItems() {
		$this->testEnvironment->insertProperty( ( new InstanceOfProperty() )->newProperty() );

		$this->testEnvironment->insertItem( ( new City() )->newItem() );
		$this->testEnvironment->insertItem( ( new Berlin() )->newItem() );

		$this->testEnvironment->insertItem( ( new State() )->newItem() );
		$this->testEnvironment->insertItem( ( new Germany() )->newItem() );

		$this->testEnvironment->insertItem( ( new WikimediaProject() )->newItem() );
		$this->testEnvironment->insertItem( ( new Wikidata() )->newItem() );
	}

	private function getCityPreJson() {
		return (object)[
			'label'  => 'city',
			'id'  => 'Q515',
			'url'  => 'http://test.url/items/Q515',
			'wikidata_url'  => 'https://www.wikidata.org/entity/Q515'
		];
	}

	private function getStatePreJson() {
		return (object)[
			'label'  => 'state',
			'id'  => 'Q7275',
			'url'  => 'http://test.url/items/Q7275',
			'wikidata_url'  => 'https://www.wikidata.org/entity/Q7275'
		];
	}

	private function getWmfProjectPreJson() {
		return (object)[
			'label'  => 'Wikimedia project',
			'id'  => 'Q14827288',
			'url'  => 'http://test.url/items/Q14827288',
			'wikidata_url'  => 'https://www.wikidata.org/entity/Q14827288'
		];
	}

	public function testWhenThreeTypes_theyAreAllShown() {
		$this->storeThreeInstanceOfReferencedItems();
		$client = $this->createClient();

		$client->request( 'GET', '/items/types' );

		$this->assertSuccessResponse(
			[
				$this->getCityPreJson(),
				$this->getStatePreJson(),
				$this->getWmfProjectPreJson(),
			],
			$client->getResponse()
		);
	}

	public function testGivenPageSize_onlyThatManyTypesAreShown() {
		$this->storeThreeInstanceOfReferencedItems();
		$client = $this->createClient();

		$client->request( 'GET', '/items/types?per_page=2' );

		$this->assertSuccessResponse(
			[
				$this->getCityPreJson(),
				$this->getStatePreJson(),
			],
			$client->getResponse()
		);
	}

	public function testGivenPageOffsetWhenFurtherItems_onlyFurtherTypesAreShown() {
		$this->storeThreeInstanceOfReferencedItems();
		$client = $this->createClient();

		$client->request( 'GET', '/items/types?per_page=2&page=2' );

		$this->assertSuccessResponse(
			[
				$this->getWmfProjectPreJson(),
			],
			$client->getResponse()
		);
	}

	public function testGivenPageOffsetBeyondLastItemType_noTypesAreShown() {
		$this->storeThreeInstanceOfReferencedItems();
		$client = $this->createClient();

		$client->request( 'GET', '/items/types?page=2' );

		$this->assertSuccessResponse( [], $client->getResponse() );
	}

	public function testGivenPageOffsetBeyondLastType_noNextLinkHeaderIsSet() {
		$this->storeThreeInstanceOfReferencedItems();
		$client = $this->createClient();

		$client->request( 'GET', '/items/types?page=2' );

		$this->assertLinkRelNotSet( $client, 'next' );
	}

	public function testGivenPageWithLessThanMaxTypes_noNextLinkHeaderIsSet() {
		$this->storeThreeInstanceOfReferencedItems();
		$client = $this->createClient();

		$client->request( 'GET', '/items/types?per_page=5' );

		$this->assertLinkRelNotSet( $client, 'next' );
	}

	public function testWhenOnFirstPage_noFirstLinkHeaderIsSet() {
		$this->storeThreeInstanceOfReferencedItems();
		$client = $this->createClient();

		$client->request( 'GET', '/items/types' );

		$this->assertLinkRelNotSet( $client, 'first' );
	}

	public function testWhenFurtherResults_nextLinkHeaderIsSet() {
		$this->storeThreeInstanceOfReferencedItems();
		$client = $this->createClient();

		$client->request( 'GET', '/items/types?per_page=2' );

		$this->assertLinkRel(
			$client,
			'next',
			'<http://localhost/items/types?page=2&per_page=2>; rel="next"'
		);
	}

	public function testNotOnFirstPage_firstLinkHeaderIsSet() {
		$this->storeThreeInstanceOfReferencedItems();
		$client = $this->createClient();

		$client->request( 'GET', '/items/types?page=42&per_page=23' );

		$this->assertLinkRel(
			$client,
			'first',
			'<http://localhost/items/types?page=1&per_page=23>; rel="first"'
		);
	}

}