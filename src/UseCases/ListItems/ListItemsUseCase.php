<?php

namespace Queryr\WebApi\UseCases\ListItems;

use Queryr\Resources\ItemList;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ListItemsUseCase {

	public function listItems( ItemListingRequest $request ): ItemList {
		return new ItemList( [

		] );
	}

}