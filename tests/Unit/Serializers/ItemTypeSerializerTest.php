<?php

namespace Tests\Queryr\Serialization;

use Queryr\WebApi\UseCases\ListItemTypes\ItemType;
use Queryr\WebApi\Serializers\SerializerFactory;
use Serializers\Exceptions\UnsupportedObjectException;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @covers Queryr\WebApi\Serializers\ItemTypeSerializer
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemTypeSerializerTest extends \PHPUnit_Framework_TestCase {

	public function testGivenNonItemType_exceptionIsThrown() {
		$serializer = ( new SerializerFactory() )->newItemTypeSerializer();

		$this->setExpectedException( UnsupportedObjectException::class );
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
