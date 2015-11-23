<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Wikibase\DataFixtures\Items\Berlin;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemDescriptionEndpointTest extends ApiTestCase {

	public function testGivenNotKnownItemId_404isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/items/Q900404/description' );

		$this->assert404( $client->getResponse() );
	}

	public function testGivenNonItemId_404isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/items/YouMadBro/description' );

		$this->assert404( $client->getResponse(), 'No route found for "GET /items/YouMadBro/description"' );
	}

	public function testGivenKnownItemId_itemDescriptionIsReturned() {
		$this->testEnvironment->insertItem( ( new Berlin() )->newItem() );

		$client = $this->createClient();

		$client->request( 'GET', '/items/Q64/description' );

		$this->assertSuccessResponse(
			'capital city and state of Germany',
			$client->getResponse()
		);
	}

}
