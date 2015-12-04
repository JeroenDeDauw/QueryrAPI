<?php

namespace Tests\Queryr\Serialization;

use DataValues\NumberValue;
use DataValues\StringValue;
use Queryr\WebApi\ResponseModel\SimpleStatement;
use Queryr\WebApi\UseCases\GetProperty\SimpleProperty;
use Queryr\WebApi\Serializers\SerializerFactory;
use Serializers\Exceptions\UnsupportedObjectException;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @covers Queryr\WebApi\Serializers\SimplePropertySerializer
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimplePropertySerializerTest extends \PHPUnit_Framework_TestCase {

	public function testGivenNonItem_exceptionIsThrown() {
		$serializer = ( new SerializerFactory() )->newSimplePropertySerializer();

		$this->setExpectedException( UnsupportedObjectException::class );
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

		$property->statements = [
			SimpleStatement::newInstance()
				->withPropertyId( new PropertyId( 'P1' ) )
				->withPropertyName( 'fluffiness' )
				->withType( 'number' )
				->withValues( [ new NumberValue( 9001 ) ] ),

			SimpleStatement::newInstance()
				->withPropertyId( new PropertyId( 'P2' ) )
				->withPropertyName( 'awesome' )
				->withType( 'string' )
				->withValues( [ new StringValue( 'Jeroen' ), new StringValue( 'Abraham' ) ] ),
		];

		$property->type = 'awesome';

		$property->labelUrl = 'http://labels';
		$property->descriptionUrl = 'http://description';
		$property->aliasesUrl = 'http://aliases';
		$property->dataUrl = 'http://data';
		$property->wikidataUrl = 'http://wikidata';
		$property->wikipediaHtmlUrl = 'http://wikipedia';

		return $property;
	}

	public function testSerializationWithValueForTwoProperties() {
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

			'label_url' => 'http://labels',
			'description_url' => 'http://description',
			'aliases_url' => 'http://aliases',
			'wikidata_url' => 'http://wikidata',

			'data_url' => 'http://data',
			'data' => [
				'P1' => [
					'value' => 9001,
					'type' => 'number'
				],
				'P2' => [
					'value' => 'Jeroen',
					'values' => [ 'Jeroen', 'Abraham' ],
					'type' => 'string'
				],
			]
		];

		$this->assertEquals( $expected, $serialized );
	}

}
