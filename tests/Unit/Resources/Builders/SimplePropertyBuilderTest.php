<?php

namespace Tests\Queryr\Resources\Builders;

use Queryr\Resources\Builders\SimplePropertyBuilder;
use Queryr\Resources\SimpleProperty;
use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\Term\Fingerprint;

/**
 * @covers Queryr\Resources\Builders\SimplePropertyBuilder
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimplePropertyBuilderTest extends \PHPUnit_Framework_TestCase {

	private function newProperty() {
		$property = Property::newFromType( 'kittens' );

		$property->setId( 1337 );

		$property->setFingerprint( $this->newFingerprint() );

		return $property;
	}

	private function newFingerprint() {
		$fingerprint = new Fingerprint();

		$fingerprint->setLabel( 'en', 'foo' );
		$fingerprint->setLabel( 'de', 'bar' );
		$fingerprint->setLabel( 'nl', 'baz' );

		$fingerprint->setDescription( 'de', 'de description' );

		$fingerprint->setAliasGroup( 'en', [ 'first en alias', 'second en alias' ] );
		$fingerprint->setAliasGroup( 'de', [ 'first de alias', 'second de alias' ] );

		return $fingerprint;
	}

	public function testSerializationForDe() {
		$simpleProperty = $this->buildNewSimplePropertyForLanguage( 'de' );

		$expected = new SimpleProperty();
		$expected->ids = [
			'wikidata' => 'P1337',
		];

		$expected->label = 'bar';
		$expected->description = 'de description';
		$expected->aliases = [ 'first de alias', 'second de alias' ];

		$expected->type = 'kittens';

		$this->assertEquals( $expected, $simpleProperty );
	}

	private function buildNewSimplePropertyForLanguage( $languageCode ) {
		$labelLookup = $this->getMock( 'Queryr\Resources\Builders\ResourceLabelLookup' );

		$labelLookup->expects( $this->any() )
			->method( 'getLabelByIdAndLanguage' )
			->will( $this->returnValue( 'awesome label' ) );

		$propertyBuilder = new SimplePropertyBuilder( $languageCode );

		return $propertyBuilder->buildFromProperty( $this->newProperty() );
	}

	public function testSerializationForEn() {
		$simpleProperty = $this->buildNewSimplePropertyForLanguage( 'en' );

		$expected = new SimpleProperty();
		$expected->ids = [
			'wikidata' => 'P1337',
		];

		$expected->label = 'foo';
		$expected->aliases = [ 'first en alias', 'second en alias' ];

		$expected->type = 'kittens';

		$this->assertEquals( $expected, $simpleProperty );
	}

	public function testSerializationForNl() {
		$simpleProperty = $this->buildNewSimplePropertyForLanguage( 'nl' );

		$expected = new SimpleProperty();
		$expected->ids = [
			'wikidata' => 'P1337',
		];

		$expected->label = 'baz';

		$expected->type = 'kittens';

		$this->assertEquals( $expected, $simpleProperty );
	}

}
