<?php

namespace Tests\Queryr\Resources\Builders;

use Queryr\Resources\ItemList;
use Queryr\Resources\ItemListElement;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @covers Queryr\Resources\ItemList
 * @covers Queryr\Resources\ItemListElement
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemListTest extends \PHPUnit_Framework_TestCase {

	public function testSetAndGetElements() {
		$items = [
			( new ItemListElement() )
				->setItemId( new ItemId( 'Q1' ) )
				->setLastUpdate( '2014-08-16T19:52:04Z' )
				->setWikidataPageUrl( 'https://www.wikidata.org/entity/Q1' )
				->setQueryrApiUrl( 'http://api.queryr.com/items/Q1' ),

			( new ItemListElement() )
				->setItemId( new ItemId( 'Q2' ) )
				->setLastUpdate( '2014-05-30T16:31:27Z' )
				->setWikidataPageUrl( 'https://www.wikidata.org/entity/Q2' )
				->setQueryrApiUrl( 'http://api.queryr.com/items/Q2' )
		];

		$list = new ItemList( $items );
		$this->assertSame( $items, $list->getElements() );
	}

	public function testItemListElementWithNoOptionalFields() {
		$item = ( new ItemListElement() )
			->setItemId( new ItemId( 'Q1' ) )
			->setLastUpdate( '2014-08-16T19:52:04Z' )
			->setWikidataPageUrl( 'https://www.wikidata.org/entity/Q1' )
			->setQueryrApiUrl( 'http://api.queryr.com/items/Q1' );

		$this->assertEquals( new ItemId( 'Q1' ), $item->getItemId() );
		$this->assertSame( '2014-08-16T19:52:04Z', $item->getLastUpdateTime() );
		$this->assertSame( 'https://www.wikidata.org/entity/Q1', $item->getWikidataUrl() );
		$this->assertSame( 'http://api.queryr.com/items/Q1', $item->getQueryrApiUrl() );
		$this->assertNull( $item->getLabel() );
		$this->assertNull( $item->getWikipediaPageUrl() );
	}

	public function testItemListElementWithAllOptionalFields() {
		$item = ( new ItemListElement() )
			->setItemId( new ItemId( 'Q1' ) )
			->setLastUpdate( '2014-08-16T19:52:04Z' )
			->setWikidataPageUrl( 'https://www.wikidata.org/entity/Q1' )
			->setQueryrApiUrl( 'http://api.queryr.com/items/Q1' )
			->setLabel( 'kittens' )
			->setWikipediaPageUrl( 'http://its-a-wikipedia' );

		$this->assertEquals( new ItemId( 'Q1' ), $item->getItemId() );
		$this->assertSame( '2014-08-16T19:52:04Z', $item->getLastUpdateTime() );
		$this->assertSame( 'https://www.wikidata.org/entity/Q1', $item->getWikidataUrl() );
		$this->assertSame( 'http://api.queryr.com/items/Q1', $item->getQueryrApiUrl() );
		$this->assertSame( 'kittens', $item->getLabel() );
		$this->assertSame( 'http://its-a-wikipedia', $item->getWikipediaPageUrl() );
	}

}
