<?php

namespace Tests\Queryr\Serialization;

use DataValues\NumberValue;
use DataValues\StringValue;
use Queryr\WebApi\UseCases\GetItem\SimpleItem;
use Queryr\WebApi\ResponseModel\SimpleStatement;
use Queryr\WebApi\Serializers\SerializerFactory;

/**
 * @covers Queryr\WebApi\Serializers\SimpleItemSerializer
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimpleItemSerializerTest extends \PHPUnit_Framework_TestCase {

	public function testGivenNonItem_exceptionIsThrown() {
		$serializer = ( new SerializerFactory() )->newSimpleItemSerializer();

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
				->withPropertyName( 'fluffiness' )
				->withType( 'number' )
				->withValues( [ new NumberValue( 9001 ) ] ),

			SimpleStatement::newInstance()
				->withPropertyName( 'awesome' )
				->withType( 'string' )
				->withValues( [ new StringValue( 'Jeroen' ), new StringValue( 'Abraham' ) ] ),
		];

		$item->labelUrl = 'http://labels';
		$item->descriptionUrl = 'http://description';
		$item->aliasesUrl = 'http://aliases';
		$item->dataUrl = 'http://data';
		$item->wikidataUrl = 'http://wikidata';
		$item->wikipediaHtmlUrl = 'http://wikipedia';

		return $item;
	}

	public function testSerializationWithValueForTwoProperties() {
		$serializer = ( new SerializerFactory() )->newSimpleItemSerializer();
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

			'label_url' => 'http://labels',
			'description_url' => 'http://description',
			'aliases_url' => 'http://aliases',
			'wikidata_url' => 'http://wikidata',

			'data_url' => 'http://data',
			'data' => [
				'fluffiness' => [
					'value' => 9001,
					'type' => 'number'
				],
				'awesome' => [
					'value' => 'Jeroen',
					'values' => [ 'Jeroen', 'Abraham' ],
					'type' => 'string'
				],
			]
		];

		$this->assertEquals( $expected, $serialized );
	}

}
