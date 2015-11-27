<?php

namespace Tests\Queryr\Resources\Builders;

use Queryr\Resources\Builders\BuilderFactory;

/**
 * @covers Queryr\Resources\Builders\BuilderFactory
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class BuilderFactoryTest extends \PHPUnit_Framework_TestCase {

	public function testNewSimpleItemBuilderReturnsCorrectType() {
		$simpleItemBuilder = ( new BuilderFactory() )->newSimpleItemBuilder(
			'en',
			$this->getMock( 'Queryr\Resources\Builders\ResourceLabelLookup' )
		);

		$this->assertInstanceOf( 'Queryr\Resources\Builders\SimpleItemBuilder', $simpleItemBuilder );
	}

	public function testNewSimplePropertyBuilderReturnsCorrectType() {
		$simplePropertyBuilder = ( new BuilderFactory() )->newSimplePropertyBuilder( 'en' );

		$this->assertInstanceOf( 'Queryr\Resources\Builders\SimplePropertyBuilder', $simplePropertyBuilder );
	}

}
