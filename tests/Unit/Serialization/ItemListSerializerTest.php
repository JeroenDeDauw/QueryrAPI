<?php

namespace Tests\Queryr\Serialization;

use Queryr\Resources\ItemList;
use Queryr\Resources\ItemListElement;
use Queryr\Serialization\SerializerFactory;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @covers Queryr\Serialization\ItemListSerializer
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemListSerializerTest extends \PHPUnit_Framework_TestCase {

	public function testGivenNonItem_exceptionIsThrown() {
		$serializer = ( new SerializerFactory() )->newItemListSerializer();

		$this->setExpectedException( 'Serializers\Exceptions\UnsupportedObjectException' );
		$serializer->serialize( null );
	}

	public function testSerialize() {
		$input = new ItemList( [
			( new ItemListElement() )
				->setItemId( new ItemId( 'Q1' ) )
				->setLastUpdate( '2014-08-16T19:52:04Z' )
				->setWikidataPageUrl( 'http://www.wikidata.org/entity/Q1' )
				->setQueryrApiUrl( 'http://api.queryr.com/items/Q1' ),

			( new ItemListElement() )
				->setItemId( new ItemId( 'Q2' ) )
				->setLastUpdate( '2014-05-30T16:31:27Z' )
				->setWikidataPageUrl( 'http://www.wikidata.org/entity/Q2' )
				->setQueryrApiUrl( 'http://api.queryr.com/items/Q2' )
				->setLabel( 'kittens' )
				->setWikipediaPageUrl( 'foo' )
		] );

		$expected = [
			[
				'id' => 'Q1',
				'updated_at' => '2014-08-16T19:52:04Z',
				'url' => 'http://api.queryr.com/items/Q1',
				'wikidata_url' => 'http://www.wikidata.org/entity/Q1',
			],
			[
				'id' => 'Q2',
				'label' => 'kittens',
				'updated_at' => '2014-05-30T16:31:27Z',
				'url' => 'http://api.queryr.com/items/Q2',
				'wikidata_url' => 'http://www.wikidata.org/entity/Q2',
				'wikipedia_url' => 'foo',
			]
		];

		$output = ( new SerializerFactory() )->newItemListSerializer()->serialize( $input );

		$this->assertEquals( $expected, $output );
	}

}
