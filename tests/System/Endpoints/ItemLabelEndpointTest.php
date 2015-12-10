<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Wikibase\DataFixtures\Items\Berlin;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemLabelEndpointTest extends ApiTestCase {

	public function testGivenNotKnownItemId_404isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/items/Q900404/label' );

		$this->assert404( $client->getResponse() );
	}

	public function testGivenNonItemId_400isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/items/YouMadBro/label' );

		$this->assert400( $client->getResponse(), 'Invalid id' );
	}

	public function testGivenKnownItemId_itemLabelIsReturned() {
		$this->testEnvironment->insertItem( ( new Berlin() )->newItem() );

		$client = $this->createClient();

		$client->request( 'GET', '/items/Q64/label' );

		$this->assertSuccessResponse(
			'Berlin',
			$client->getResponse()
		);
	}

}
