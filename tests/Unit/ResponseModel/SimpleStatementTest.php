<?php

namespace Queryr\WebApi\Tests\Unit\ResponseModel;

use Queryr\WebApi\ResponseModel\SimpleStatement;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @covers Queryr\WebApi\ResponseModel\SimpleStatement
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimpleStatementTest extends \PHPUnit_Framework_TestCase {

	public function testSafeAccessWhenNothingSetCausesException() {
		$ss = new SimpleStatement();

		$this->setExpectedException( \RuntimeException::class );
		$ss->get()->propertyId;
	}

	public function testSafeAccessWhenOnlyUrlAndIdSetCausesException() {
		$ss = ( new SimpleStatement() )
			->withPropertyUrl( 'http://foo' )
			->withPropertyName( 'kittens' );

		$this->setExpectedException( \RuntimeException::class );
		$ss->get()->propertyUrl;
	}

	public function testSafeAccessWhenAllSetDoesNotCauseException() {
		$ss = ( new SimpleStatement() )
			->withPropertyId( new PropertyId( 'P1' ) )
			->withPropertyUrl( 'http://foo' )
			->withPropertyName( 'kittens' )
			->withType( 'string' )
			->withValues( [ 'foo' ] );

		$this->assertSame( 'kittens', $ss->get()->propertyName );
	}

}
