<?php

namespace Queryr\WebApi\Tests\Integration\UseCases;

use Queryr\Resources\ItemList;
use Queryr\WebApi\UseCases\ListItems\ItemListingRequest;
use Queryr\WebApi\Tests\TestEnvironment;

/**
 * @covers Queryr\WebApi\UseCases\ListItems\ListItemsUseCase
 * @covers Queryr\WebApi\UseCases\ListItems\ItemListingRequest
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ListItemsUseCaseTest extends \PHPUnit_Framework_TestCase {

	public function testWhenNoItems_emptyListIsReturned() {
		$useCase = TestEnvironment::newInstance()->getServices()->newListItemsUseCase();

		$request = new ItemListingRequest();
		$request->setPerPage( 100 );

		$this->assertEquals(
			new ItemList( [] ),
			$useCase->listItems( $request )
		);
	}

}
