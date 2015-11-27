<?php

namespace Tests\Queryr\Serialization;

use Queryr\Resources\ItemType;
use Queryr\Serialization\SerializerFactory;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @covers Queryr\Serialization\ItemTypeSerializer
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemTypeSerializerTest extends \PHPUnit_Framework_TestCase {

	public function testGivenNonItemType_exceptionIsThrown() {
		$serializer = ( new SerializerFactory() )->newItemTypeSerializer();

		$this->setExpectedException( 'Serializers\Exceptions\UnsupportedObjectException' );
		$serializer->serialize( null );
	}

	public function testSerialize() {
		$input = new ItemType();
		$input->setLabel( 'City' );
		$input->setItemId( new ItemId( 'Q42' ) );
		$input->setApiUrl( 'http://api.queryr.com/items/Q42' );
		$input->setWikidataUrl( 'http://www.wikidata.org/entity/Q42' );

		$expected = [
			'label' => 'City',
			'id' => 'Q42',
			'url' => 'http://api.queryr.com/items/Q42',
			'wikidata_url' => 'http://www.wikidata.org/entity/Q42',
		];

		$output = ( new SerializerFactory() )->newItemTypeSerializer()->serialize( $input );

		$this->assertSame( $expected, $output );
	}

}
