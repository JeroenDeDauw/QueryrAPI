<?php

namespace Queryr\WebApi\Tests\Integration\UseCases;

use Queryr\WebApi\UseCases\ListItems\ItemList;
use Queryr\WebApi\Tests\TestEnvironment;
use Queryr\WebApi\UseCases\ListItems\ItemListingRequest;

/**
 * @covers Queryr\WebApi\UseCases\ListItems\ListItemsUseCase
 * @covers Queryr\WebApi\UseCases\ListItems\ItemListingRequest
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ListItemsUseCaseTest extends \PHPUnit_Framework_TestCase {

	public function testWhenNoItems_emptyListIsReturned() {
		$useCase = TestEnvironment::newInstance()->getFactory()->newListItemsUseCase();

		$request = new ItemListingRequest();
		$request->setPerPage( 100 );
		$request->setPage( 1 );

		$this->assertEquals(
				new ItemList( [] ),
				$useCase->listItems( $request )
		);
	}

}
