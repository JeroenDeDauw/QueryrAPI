<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Wikibase\DataFixtures\Items\Berlin;
use Wikibase\DataFixtures\Items\City;
use Wikibase\DataFixtures\Items\Germany;

/**
 * @covers Queryr\WebApi\UseCases\ListItems\ListItemsUseCase
 * @covers Queryr\WebApi\UseCases\ListItems\ItemListingRequest
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemsEndpointTest extends ApiTestCase {

	public function testItemsEndpointReturnsEmptyJsonArray() {
		$client = $this->createClient();

		$client->request( 'GET', '/items' );

		$this->assertJsonResponse( [], $client->getResponse() );
	}

	private function storeThreeItems() {
		$this->testEnvironment->insertItem( ( new Germany() )->newItem() );
		$this->testEnvironment->insertItem( ( new Berlin() )->newItem() );
		$this->testEnvironment->insertItem( ( new City() )->newItem() );
	}

	private function getBerlinPreJson() {
		return (object)[
			'id'  => 'Q64',
			'label'  => 'Berlin',
			'updated_at'  => '0000',
			'url'  => 'http://test.url/items/Q64',
			'wikidata_url'  => 'https://www.wikidata.org/entity/Q64',
			'wikipedia_url'  => 'https://www.wikidata.org/wiki/Special:GoToLinkedPage/enwiki/Q64',
		];
	}

	private function getGermanyPreJson() {
		return (object)[
			'id'  => 'Q183',
			'label'  => 'Germany',
			'updated_at'  => '0000',
			'url'  => 'http://test.url/items/Q183',
			'wikidata_url'  => 'https://www.wikidata.org/entity/Q183',
			'wikipedia_url'  => 'https://www.wikidata.org/wiki/Special:GoToLinkedPage/enwiki/Q183',
		];
	}

	private function getCityPreJson() {
		return (object)[
			'id'  => 'Q515',
			'label'  => 'city',
			'updated_at'  => '0000',
			'url'  => 'http://test.url/items/Q515',
			'wikidata_url'  => 'https://www.wikidata.org/entity/Q515',
			'wikipedia_url'  => 'https://www.wikidata.org/wiki/Special:GoToLinkedPage/enwiki/Q515',
		];
	}

	public function testWhenThreeItems_theyAreAllShown() {
		$this->storeThreeItems();
		$client = $this->createClient();

		$client->request( 'GET', '/items' );

		$this->assertJsonResponse(
			[
				$this->getBerlinPreJson(),
				$this->getGermanyPreJson(),
				$this->getCityPreJson(),
			],
			$client->getResponse()
		);
	}

	public function testGivenPageSize_onlyThatManyItemsAreShown() {
		$this->storeThreeItems();
		$client = $this->createClient();

		$client->request( 'GET', '/items?per_page=2' );

		$this->assertJsonResponse(
			[
				$this->getBerlinPreJson(),
				$this->getGermanyPreJson(),
			],
			$client->getResponse()
		);
	}

	public function testGivenPageOffsetWhenFurtherItems_onlyFurtherItemsAreShown() {
		$this->storeThreeItems();
		$client = $this->createClient();

		$client->request( 'GET', '/items?per_page=2&page=2' );

		$this->assertJsonResponse(
			[
				$this->getCityPreJson(),
			],
			$client->getResponse()
		);
	}

	public function testGivenPageOffsetBeyondLastItem_noItemsAreShown() {
		$this->storeThreeItems();
		$client = $this->createClient();

		$client->request( 'GET', '/items?page=2' );

		$this->assertJsonResponse( [], $client->getResponse() );
	}

}
