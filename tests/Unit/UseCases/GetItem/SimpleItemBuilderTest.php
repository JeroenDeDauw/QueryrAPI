<?php

namespace Queryr\WebApi\Tests\Unit\UseCases\GetItem;

use DataValues\StringValue;
use Queryr\TermStore\LabelLookup;
use Queryr\WebApi\UrlBuilder;
use Queryr\WebApi\UseCases\GetItem\SimpleItemBuilder;
use Queryr\WebApi\ResponseModel\SimpleStatementsBuilder;
use Queryr\WebApi\UseCases\GetItem\SimpleItem;
use Queryr\WebApi\ResponseModel\SimpleStatement;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\SiteLinkList;
use Wikibase\DataModel\Snak\PropertyNoValueSnak;
use Wikibase\DataModel\Snak\PropertyValueSnak;
use Wikibase\DataModel\Statement\Statement;
use Wikibase\DataModel\Term\Fingerprint;

/**
 * @covers Queryr\WebApi\UseCases\GetItem\SimpleItemBuilder
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimpleItemBuilderTest extends \PHPUnit_Framework_TestCase {

	private function newItem() {
		$item = new Item();

		$item->setId( 1337 );

		$item->setFingerprint( $this->newFingerprint() );
		$item->setSiteLinkList( $this->newSiteLinks() );

		$this->addStatements( $item );

		return $item;
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

	private function newSiteLinks() {
		$links = new SiteLinkList();

		$links->addNewSiteLink( 'enwiki', 'En Page' );
		$links->addNewSiteLink( 'dewiki', 'De Page' );

		return $links;
	}

	private function addStatements( Item $item ) {
		$statement = new Statement( new PropertyValueSnak( 42, new StringValue( 'kittens' ) ) );
		$statement->setGuid( 'first guid' );

		$item->getStatements()->addStatement( $statement );

		$statement = new Statement( new PropertyNoValueSnak( 23 ) );
		$statement->setGuid( 'second guid' );

		$item->getStatements()->addStatement( $statement );
	}

	public function testSerializationForDe() {
		$simpleItem = $this->buildNewSimpleItemForLanguage( 'de' );

		$expected = new SimpleItem();
		$expected->ids = [
			'wikidata' => 'Q1337',
			'en_wikipedia' => 'En Page',
			'de_wikipedia' => 'De Page',
		];

		$expected->label = 'bar';
		$expected->description = 'de description';
		$expected->aliases = [ 'first de alias', 'second de alias' ];

		$expected->statements = [ $this->getSimpleStatement() ];

		$expected->labelUrl = 'http://test/items/Q1337/label';
		$expected->descriptionUrl = 'http://test/items/Q1337/description';
		$expected->aliasesUrl = 'http://test/items/Q1337/aliases';
		$expected->dataUrl = 'http://test/items/Q1337/data';
		$expected->wikidataUrl = 'https://www.wikidata.org/entity/Q1337';
		$expected->wikipediaHtmlUrl = 'https://www.wikidata.org/wiki/Special:GoToLinkedPage/enwiki/Q1337';

		$this->assertEquals( $expected, $simpleItem );
	}

	private function buildNewSimpleItemForLanguage( $languageCode ) {
		$labelLookup = $this->getMock( LabelLookup::class );

		$labelLookup->expects( $this->any() )
			->method( 'getLabelByIdAndLanguage' )
			->will( $this->returnValue( 'awesome label' ) );

		$itemBuilder = new SimpleItemBuilder(
			$languageCode,
			new SimpleStatementsBuilder( $languageCode, $labelLookup ),
			new UrlBuilder( 'http://test' )
		);

		return $itemBuilder->buildFromItem( $this->newItem() );
	}

	private function getSimpleStatement() {
		return SimpleStatement::newInstance()
			->withPropertyName( 'awesome label' )
			->withPropertyId( new PropertyId( 'P42' ) )
			->withType( 'string' )
			->withValues( [ new StringValue( 'kittens' ) ] );
	}

	public function testSerializationForEn() {
		$simpleItem = $this->buildNewSimpleItemForLanguage( 'en' );

		$expected = new SimpleItem();
		$expected->ids = [
			'wikidata' => 'Q1337',
			'en_wikipedia' => 'En Page',
		];

		$expected->label = 'foo';
		$expected->aliases = [ 'first en alias', 'second en alias' ];

		$expected->statements = [ $this->getSimpleStatement() ];

		$expected->labelUrl = 'http://test/items/Q1337/label';
		$expected->descriptionUrl = 'http://test/items/Q1337/description';
		$expected->aliasesUrl = 'http://test/items/Q1337/aliases';
		$expected->dataUrl = 'http://test/items/Q1337/data';
		$expected->wikidataUrl = 'https://www.wikidata.org/entity/Q1337';
		$expected->wikipediaHtmlUrl = 'https://www.wikidata.org/wiki/Special:GoToLinkedPage/enwiki/Q1337';

		$this->assertEquals( $expected, $simpleItem );
	}

	public function testSerializationForNl() {
		$simpleItem = $this->buildNewSimpleItemForLanguage( 'nl' );

		$expected = new SimpleItem();
		$expected->ids = [
			'wikidata' => 'Q1337',
			'en_wikipedia' => 'En Page',
		];

		$expected->label = 'baz';

		$expected->statements = [ $this->getSimpleStatement() ];

		$expected->labelUrl = 'http://test/items/Q1337/label';
		$expected->descriptionUrl = 'http://test/items/Q1337/description';
		$expected->aliasesUrl = 'http://test/items/Q1337/aliases';
		$expected->dataUrl = 'http://test/items/Q1337/data';
		$expected->wikidataUrl = 'https://www.wikidata.org/entity/Q1337';
		$expected->wikipediaHtmlUrl = 'https://www.wikidata.org/wiki/Special:GoToLinkedPage/enwiki/Q1337';

		$this->assertEquals( $expected, $simpleItem );
	}

}
