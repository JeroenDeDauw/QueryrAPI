<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemAliasesEndpointTest extends ApiTestCase {

	// https://github.com/JeroenDeDauw/QueryrAPI/issues/14
//	public function testGivenNotKnownItemId_404isReturned() {
//		$client = $this->createClient();
//
//		$client->request( 'GET', '/items/Q900404/aliases' );
//
//		$this->assert404( $client->getResponse() );
//	}

	public function testGivenNonItemId_400isReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/items/YouMadBro/aliases' );

		$this->assert400( $client->getResponse(), 'Invalid id' );
	}

	public function testGivenKnownItemId_itemLabelIsReturned() {
		$item = new Item( new ItemId( 'Q1337' ) );
		$item->getFingerprint()->setAliasGroup( 'en', [ 'foo', 'bar', 'baz' ] );

		$this->testEnvironment->insertItem( $item );

		$client = $this->createClient();

		$client->request( 'GET', '/items/Q1337/aliases' );

		$this->assertSuccessResponse(
			[ 'foo', 'bar', 'baz' ],
			$client->getResponse()
		);
	}

}
