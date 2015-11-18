<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Wikibase\DataFixtures\Items\Berlin;
use Wikibase\DataFixtures\Items\City;
use Wikibase\DataFixtures\Items\Germany;

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

}
