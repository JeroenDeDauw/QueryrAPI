<?php

namespace Tests\Queryr\Serialization;

use Queryr\Resources\SimpleProperty;
use Queryr\Serialization\SerializerFactory;

/**
 * @covers Queryr\Serialization\SimplePropertySerializer
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimplePropertySerializerTest extends \PHPUnit_Framework_TestCase {

	public function testGivenNonItem_exceptionIsThrown() {
		$serializer = ( new SerializerFactory() )->newSimplePropertySerializer();

		$this->setExpectedException( 'Serializers\Exceptions\UnsupportedObjectException' );
		$serializer->serialize( null );
	}

	private function newSimpleProperty() {
		$property = new SimpleProperty();

		$property->ids = [
			'wikidata' => 'Q1337',
		];

		$property->label = 'kittens';
		$property->description = 'lots of kittens';
		$property->aliases = [ 'cats' ];

		$property->type = 'awesome';

		return $property;
	}

	public function testSerializationWithValueForOneProperty() {
		$serializer = ( new SerializerFactory() )->newSimplePropertySerializer();
		$serialized = $serializer->serialize( $this->newSimpleProperty() );

		$expected = [
			'id' => [
				'wikidata' => 'Q1337',
			],

			'label' => 'kittens',
			'description' => 'lots of kittens',
			'aliases' => [ 'cats' ],

			'type' => 'awesome',
		];

		$this->assertEquals( $expected, $serialized );
	}

}
