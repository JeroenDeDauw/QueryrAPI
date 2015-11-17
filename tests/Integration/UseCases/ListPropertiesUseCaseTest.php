<?php

namespace Queryr\WebApi\Tests\Integration\UseCases;

use Queryr\Resources\PropertyList;
use Queryr\WebApi\Tests\TestEnvironment;
use Queryr\WebApi\UseCases\ListProperties\PropertyListingRequest;

/**
 * @covers Queryr\WebApi\UseCases\ListProperties\ListPropertiesUseCase
 * @covers Queryr\WebApi\UseCases\ListProperties\PropertyListingRequest
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ListPropertiesUseCaseTest extends \PHPUnit_Framework_TestCase {

	public function testWhenNoProperties_emptyListIsReturned() {
		$useCase = TestEnvironment::newInstance()->getServices()->newListPropertiesUseCase();

		$request = new PropertyListingRequest();
		$request->setPerPage( 100 );

		$this->assertEquals(
			new PropertyList( [] ),
			$useCase->listProperties( $request )
		);
	}

}
