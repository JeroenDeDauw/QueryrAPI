<?php

namespace Tests\Queryr\Resources\Builders;

use DataValues\StringValue;
use Queryr\Resources\Builders\SimpleStatementsBuilder;
use Queryr\Resources\SimpleStatement;
use Wikibase\DataModel\Entity\EntityIdValue;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\Snak\PropertyValueSnak;
use Wikibase\DataModel\Statement\Statement;
use Wikibase\DataModel\Statement\StatementList;

/**
 * @covers Queryr\Resources\Builders\SimpleStatementsBuilder
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
			->withType( 'string' )
			->withValues( [ new StringValue( 'kittens' ) ] );

		$this->assertBuildsFrom( [ $statement ], [ $expected ] );
	}

	private function assertBuildsFrom( array $statements, array $expected ) {
		$labelLookup = $this->getMock( 'Queryr\Resources\Builders\ResourceLabelLookup' );

		$labelLookup->expects( $this->any() )
			->method( 'getLabelByIdAndLanguage' )
			->will( $this->returnValue( 'awesome label' ) );

		$builder = new SimpleStatementsBuilder( 'en', $labelLookup );
		$simpleStatements = $builder->buildFromStatements( new StatementList( $statements ) );

		$this->assertEquals( $expected, $simpleStatements );
	}

	public function testEntityIdValueGetsSimplified() {
		$statement = new Statement( new PropertyValueSnak( 42, new EntityIdValue( new ItemId( 'Q1337' ) ) ) );
		$statement->setGuid( 'first guid' );

		$expected = SimpleStatement::newInstance()
			->withPropertyName( 'awesome label' )
			->withPropertyId( new PropertyId( 'P42' ) )
			->withType( 'string' )
			->withValues( [ new StringValue( 'awesome label' ) ] );

		$this->assertBuildsFrom( [ $statement ], [ $expected ] );
	}

	public function testLabelLookupFallsBackToId() {
		$labelLookup = $this->getMock( 'Queryr\Resources\Builders\ResourceLabelLookup' );

		$labelLookup->expects( $this->any() )
			->method( 'getLabelByIdAndLanguage' )
			->will( $this->returnValue( null ) );

		$statement = new Statement( new PropertyValueSnak( 42, new EntityIdValue( new ItemId( 'Q1337' ) ) ) );
		$statement->setGuid( 'first guid' );

		$builder = new SimpleStatementsBuilder( 'en', $labelLookup );
		$simpleStatements = $builder->buildFromStatements( new StatementList( [ $statement ] ) );

		$expected = SimpleStatement::newInstance()
			->withPropertyName( 'P42' )
			->withPropertyId( new PropertyId( 'P42' ) )
			->withType( 'string' )
			->withValues( [ new StringValue( 'Q1337' ) ] );

		$this->assertEquals( [ $expected ], $simpleStatements );
	}

}
