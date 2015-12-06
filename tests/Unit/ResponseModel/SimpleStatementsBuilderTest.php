<?php

namespace Queryr\WebApi\Tests\Unit\ResponseModel;

use DataValues\StringValue;
use Queryr\TermStore\LabelLookup;
use Queryr\WebApi\ResponseModel\DataValues\EntityIdentityValue;
use Queryr\WebApi\ResponseModel\SimpleStatementsBuilder;
use Queryr\WebApi\ResponseModel\SimpleStatement;
use Queryr\WebApi\UrlBuilder;
use Wikibase\DataModel\Entity\EntityIdValue;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\Snak\PropertyValueSnak;
use Wikibase\DataModel\Statement\Statement;
use Wikibase\DataModel\Statement\StatementList;

/**
 * @covers Queryr\WebApi\ResponseModel\SimpleStatementsBuilder
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimpleStatementsBuilderTest extends \PHPUnit_Framework_TestCase {

	public function testBuildFromSingleStatementWithPropertyValueSnak() {
		$statement = new Statement( new PropertyValueSnak( 42, new StringValue( 'kittens' ) ) );
		$statement->setGuid( 'first guid' );

		$expected = SimpleStatement::newInstance()
			->withPropertyName( 'awesome label' )
			->withPropertyId( new PropertyId( 'P42' ) )
			->withPropertyUrl( 'http://test/properties/P42' )
			->withType( 'string' )
			->withValues( [ new StringValue( 'kittens' ) ] );

		$this->assertBuildsFrom( [ $statement ], [ $expected ] );
	}

	private function assertBuildsFrom( array $statements, array $expected ) {
		$labelLookup = $this->getMock( LabelLookup::class );

		$labelLookup->expects( $this->any() )
			->method( 'getLabelByIdAndLanguage' )
			->will( $this->returnValue( 'awesome label' ) );

		$builder = new SimpleStatementsBuilder( 'en', $labelLookup, new UrlBuilder( 'http://test' ) );
		$simpleStatements = $builder->buildFromStatements( new StatementList( $statements ) );

		$this->assertEquals( $expected, $simpleStatements );
	}

	public function testEntityIdValueGetsSimplified() {
		$statement = new Statement( new PropertyValueSnak( 42, new EntityIdValue( new ItemId( 'Q1337' ) ) ) );
		$statement->setGuid( 'first guid' );

		$expected = SimpleStatement::newInstance()
			->withPropertyName( 'awesome label' )
			->withPropertyId( new PropertyId( 'P42' ) )
			->withPropertyUrl( 'http://test/properties/P42' )
			->withType( 'queryr-entity-identity' )
			->withValues( [
				new EntityIdentityValue(
					new ItemId( 'Q1337' ),
					'awesome label',
					'http://test/items/Q1337'
				)
			] );

		$this->assertBuildsFrom( [ $statement ], [ $expected ] );
	}

	public function testLabelLookupFallsBackToId() {
		$labelLookup = $this->getMock( LabelLookup::class );

		$labelLookup->expects( $this->any() )
			->method( 'getLabelByIdAndLanguage' )
			->will( $this->returnValue( null ) );

		$statement = new Statement( new PropertyValueSnak( 42, new EntityIdValue( new ItemId( 'Q1337' ) ) ) );
		$statement->setGuid( 'first guid' );

		$builder = new SimpleStatementsBuilder( 'en', $labelLookup, new UrlBuilder( 'http://test' ) );
		$simpleStatements = $builder->buildFromStatements( new StatementList( [ $statement ] ) );

		$expected = SimpleStatement::newInstance()
			->withPropertyName( 'P42' )
			->withPropertyId( new PropertyId( 'P42' ) )
			->withPropertyUrl( 'http://test/properties/P42' )
			->withType( 'queryr-entity-identity' )
			->withValues( [
				new EntityIdentityValue(
					new ItemId( 'Q1337' ),
					'Q1337',
					'http://test/items/Q1337'
				)
			] );

		$this->assertEquals( [ $expected ], $simpleStatements );
	}

}
