<?php

namespace Tests\Queryr\Serialization;

use DataValues\NumberValue;
use DataValues\StringValue;
use Queryr\Resources\SimpleItem;
use Queryr\Resources\SimpleStatement;
use Queryr\Serialization\SerializerFactory;

/**
 * @covers Queryr\Serialization\StableItemSerializer
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class StableItemSerializerTest extends \PHPUnit_Framework_TestCase {

	public function testGivenNonItem_exceptionIsThrown() {
		$serializer = ( new SerializerFactory() )->newStableItemSerializer( [] );

		$this->setExpectedException( 'Serializers\Exceptions\UnsupportedObjectException' );
		$serializer->serialize( null );
	}

	private function newSimpleItem() {
		$item = new SimpleItem();

		$item->ids = [
			'wikidata' => 'Q1337',
			'en.wikipedia' => 'Kitten',
			'de.wikipedia' => 'Katzen',
		];

		$item->label = 'kittens';
		$item->description = 'lots of kittens';
		$item->aliases = [ 'cats' ];

		$item->statements = [
			SimpleStatement::newInstance()
				->withPropertyName( 'Population prop name' )
				->withPropertyId( 'P23' )
				->withType( 'number' )
				->withValues( [ new NumberValue( 9001 ) ] ),

			SimpleStatement::newInstance()
				->withPropertyName( 'foo bar baz' )
				->withPropertyId( 'P42' )
				->withType( 'string' )
				->withValues( [ new StringValue( 'Jeroen' ), new StringValue( 'Abraham' ) ] ),

			SimpleStatement::newInstance()
				->withPropertyName( 'Property that is no in the map' )
				->withPropertyId( 'P1337' )
				->withType( 'number' )
				->withValues( [ new NumberValue( 1337 ) ] ),
		];

		return $item;
	}

	public function testSerialization() {
		$serializer = ( new SerializerFactory() )->newStableItemSerializer( [
			'P42' => 'Certified by',
			'P23' => 'Population',
		] );

		$serialized = $serializer->serialize( $this->newSimpleItem() );

		$expected = [
			'id' => [
				'wikidata' => 'Q1337',
				'en.wikipedia' => 'Kitten',
				'de.wikipedia' => 'Katzen',
			],

			'label' => 'kittens',
			'description' => 'lots of kittens',
			'aliases' => [ 'cats' ],

			'data' => [
				'Population' => [
					'value' => 9001,
					'type' => 'number'
				],
				'Certified by' => [
					'value' => 'Jeroen',
					'values' => [ 'Jeroen', 'Abraham' ],
					'type' => 'string'
				],
			]
		];

		$this->assertEquals( $expected, $serialized );
	}

}
