<?php

namespace Tests\Queryr\Serialization;

use Queryr\WebApi\UseCases\ListProperties\PropertyList;
use Queryr\WebApi\UseCases\ListProperties\PropertyListElement;
use Queryr\WebApi\Serializers\SerializerFactory;
use Serializers\Exceptions\UnsupportedObjectException;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @covers Queryr\WebApi\Serializers\PropertyListSerializer
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyListSerializerTest extends \PHPUnit_Framework_TestCase {

	public function testGivenNonItem_exceptionIsThrown() {
		$serializer = ( new SerializerFactory() )->newPropertyListSerializer();

		$this->setExpectedException( UnsupportedObjectException::class );
		$serializer->serialize( null );
	}

	public function testSerialize() {
		$input = new PropertyList( [
			new PropertyListElement(
				new PropertyId( 'P1' ),
				'number',
				'http://www.wikidata.org/entity/P1',
				'http://api.queryr.com/properties/P1'
			),
			new PropertyListElement(
				new PropertyId( 'P2' ),
				'string',
				'http://www.wikidata.org/entity/P2',
				'http://api.queryr.com/properties/P2'
			)
		] );

		$expected = [
			[
				'id' => 'P1',
				'type' => 'number',
				'url' => 'http://api.queryr.com/properties/P1',
				'wikidata_url' => 'http://www.wikidata.org/entity/P1',
			],
			[
				'id' => 'P2',
				'type' => 'string',
				'url' => 'http://api.queryr.com/properties/P2',
				'wikidata_url' => 'http://www.wikidata.org/entity/P2',
			]
		];

		$output = ( new SerializerFactory() )->newPropertyListSerializer()->serialize( $input );

		$this->assertSame( $expected, $output );
	}

}
