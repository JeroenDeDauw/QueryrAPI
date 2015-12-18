<?php

namespace Queryr\WebApi\Tests\Unit\UseCases\GetProperty;

use Queryr\TermStore\LabelLookup;
use Queryr\WebApi\ResponseModel\SimpleStatementsBuilder;
use Queryr\WebApi\UrlBuilder;
use Queryr\WebApi\UseCases\GetProperty\SimpleProperty;
use Queryr\WebApi\UseCases\GetProperty\SimplePropertyBuilder;
use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\Term\Fingerprint;

/**
 * @covers Queryr\WebApi\UseCases\GetProperty\SimplePropertyBuilder
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

		$expected->labelUrl = 'http://test/properties/P1337/label';
		$expected->descriptionUrl = 'http://test/properties/P1337/description';
		$expected->aliasesUrl = 'http://test/properties/P1337/aliases';
		$expected->dataUrl = 'http://test/properties/P1337/data';
		$expected->wikidataUrl = 'https://www.wikidata.org/entity/P1337';

		$this->assertEquals( $expected, $simpleProperty );
	}

	private function buildNewSimplePropertyForLanguage( $languageCode ) {
		$labelLookup = $this->getMock( LabelLookup::class );

		$labelLookup->expects( $this->any() )
			->method( 'getLabelByIdAndLanguage' )
			->will( $this->returnValue( 'awesome label' ) );

		$urlBuilder = new UrlBuilder( 'http://test' );

		$propertyBuilder = new SimplePropertyBuilder(
			$languageCode,
			new SimpleStatementsBuilder( $languageCode, $labelLookup, $urlBuilder ),
				$urlBuilder
		);

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

		$expected->labelUrl = 'http://test/properties/P1337/label';
		$expected->descriptionUrl = 'http://test/properties/P1337/description';
		$expected->aliasesUrl = 'http://test/properties/P1337/aliases';
		$expected->dataUrl = 'http://test/properties/P1337/data';
		$expected->wikidataUrl = 'https://www.wikidata.org/entity/P1337';

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

		$expected->labelUrl = 'http://test/properties/P1337/label';
		$expected->descriptionUrl = 'http://test/properties/P1337/description';
		$expected->aliasesUrl = 'http://test/properties/P1337/aliases';
		$expected->dataUrl = 'http://test/properties/P1337/data';
		$expected->wikidataUrl = 'https://www.wikidata.org/entity/P1337';

		$this->assertEquals( $expected, $simpleProperty );
	}

}
