<?php

declare(strict_types=1);

namespace Queryr\WebApi\UseCases\GetItem;

use Queryr\Resources\SimpleItem;
use Queryr\WebApi\NoNullableReturnTypesException;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GetItemUseCase {

	public function __construct() {
	}

	public function getItem( GetItemRequest $request ): SimpleItem {
		throw new NoNullableReturnTypesException();
	}

}