<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Queryr\WebApi\UseCases\ListItems\ItemListingRequest;
use Symfony\Component\HttpFoundation\Response;
use Wikibase\DataFixtures\Items\Berlin;
use Wikibase\DataFixtures\Items\City;
use Wikibase\DataFixtures\Items\Germany;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemsEndpointTest extends ApiTestCase {

	public function testItemsEndpointReturnsEmptyJsonArray() {
		$client = $this->createClient();

		$client->request( 'GET', '/items' );

		$this->assertJsonResponse( [], $client->getResponse() );
	}

	private function assertJsonResponse( $expected, Response $response ) {
		$this->assertTrue( $response->isSuccessful(), 'request is successful' );
		$this->assertJson( $response->getContent(), 'response is json' );

		$this->assertSame(
			json_encode( $expected, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ),
			$response->getContent()
		);
	}

	private function storeThreeItems() {
		$this->testEnvironment->insertItem( ( new Germany() )->newItem() );
		$this->testEnvironment->insertItem( ( new Berlin() )->newItem() );
		$this->testEnvironment->insertItem( ( new City() )->newItem() );
	}

	public function testWhenThreeItems_theyAreAllReturned() {
		$this->storeThreeItems();
		$client = $this->createClient();

		$client->request( 'GET', '/items' );

		$this->assertJsonResponse(
			[
				(object)[
					'id'  => 'Q64',
					'label'  => 'Berlin',
					'updated_at'  => '0000',
					'url'  => 'http://test.url/items/Q64',
					'wikidata_url'  => 'https://www.wikidata.org/entity/Q64',
					'wikipedia_url'  => 'https://www.wikidata.org/wiki/Special:GoToLinkedPage/enwiki/Q64',
				],
				(object)[
					'id'  => 'Q183',
					'label'  => 'Germany',
					'updated_at'  => '0000',
					'url'  => 'http://test.url/items/Q183',
					'wikidata_url'  => 'https://www.wikidata.org/entity/Q183',
					'wikipedia_url'  => 'https://www.wikidata.org/wiki/Special:GoToLinkedPage/enwiki/Q183',
				],
				(object)[
					'id'  => 'Q515',
					'label'  => 'city',
					'updated_at'  => '0000',
					'url'  => 'http://test.url/items/Q515',
					'wikidata_url'  => 'https://www.wikidata.org/entity/Q515',
					'wikipedia_url'  => 'https://www.wikidata.org/wiki/Special:GoToLinkedPage/enwiki/Q515',
				],
			],
			$client->getResponse()
		);
	}

}
