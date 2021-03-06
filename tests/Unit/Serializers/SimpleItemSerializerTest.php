<?php

namespace Tests\Queryr\Serialization;

use DataValues\NumberValue;
use DataValues\StringValue;
use Queryr\WebApi\ResponseModel\SimpleStatement;
use Queryr\WebApi\Tests\TestEnvironment;
use Queryr\WebApi\UseCases\GetItem\SimpleItem;
use Serializers\Exceptions\UnsupportedObjectException;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @covers Queryr\WebApi\Serializers\SimpleItemSerializer
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimpleItemSerializerTest extends \PHPUnit_Framework_TestCase {

	public function testGivenNonItem_exceptionIsThrown() {

		$serializer = TestEnvironment::newUninstalledInstance()->getFactory()->newSimpleItemSerializer();

		$this->setExpectedException( UnsupportedObjectException::class );
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
				->withPropertyId( new PropertyId( 'P1' ) )
				->withPropertyUrl( 'http://property/1' )
				->withPropertyName( 'fluffiness' )
				->withType( 'number' )
				->withValues( [ new NumberValue( 9001 ) ] ),

			SimpleStatement::newInstance()
				->withPropertyId( new PropertyId( 'P2' ) )
				->withPropertyUrl( 'http://property/2' )
				->withPropertyName( 'awesome' )
				->withType( 'string' )
				->withValues( [ new StringValue( 'Jeroen' ), new StringValue( 'Abraham' ) ] ),
		];

		$item->labelUrl = 'http://labels';
		$item->descriptionUrl = 'http://description';
		$item->aliasesUrl = 'http://aliases';
		$item->dataUrl = 'http://data';
		$item->wikidataUrl = 'http://wikidata';

		return $item;
	}

	private function newSimpleItemSerialization() {
		return [
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
				'P1' => [
					'property' => [
						'label' => 'fluffiness',
						'id' => 'P1',
						'url' => 'http://property/1',
					],
					'value' => 9001,
					'type' => 'number'
				],
				'P2' => [
					'property' => [
						'label' => 'awesome',
						'id' => 'P2',
						'url' => 'http://property/2',
					],
					'value' => 'Jeroen',
					'values' => [ 'Jeroen', 'Abraham' ],
					'type' => 'string'
				],
			]
		];
	}

	public function testSerializationWithValueForTwoProperties() {
		$serializer = TestEnvironment::newInstance()->getFactory()->newSimpleItemSerializer();

		$this->assertEquals(
			$this->newSimpleItemSerialization(),
			$serializer->serialize( $this->newSimpleItem() )
		);
	}

	public function testWithWikipediaHtmlUrl() {
		$serializer = TestEnvironment::newInstance()->getFactory()->newSimpleItemSerializer();

		$simpleItem = $this->newSimpleItem();
		$simpleItem->wikipediaHtmlUrl = 'http://wikipedia';

		$expected = $this->newSimpleItemSerialization();
		$expected['wikipedia_html_url'] = 'http://wikipedia';

		$this->assertEquals( $expected, $serializer->serialize( $simpleItem ) );
	}

}
