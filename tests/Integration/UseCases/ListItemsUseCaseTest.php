<?php

namespace Queryr\WebApi\Tests\Integration\UseCases;

use Queryr\Resources\ItemList;
use Queryr\WebApi\ApiServices;
use Queryr\WebApi\UseCases\ListItems\ItemListingRequest;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ListItemsUseCaseTest extends \PHPUnit_Framework_TestCase {

	private function getServices() {
		return new ApiServices( require __DIR__ . ' /../../../app/bootstrap.php' );
	}

	public function testWhenNoItems_emptyListIsReturned() {
		$useCase = $this->getServices()->newListItemsUseCase();

		$this->assertEquals(
			new ItemList( [] ),
			$useCase->listItems( new ItemListingRequest() )
		);
	}

}