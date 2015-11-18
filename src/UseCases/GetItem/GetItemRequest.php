<?php

declare(strict_types=1);

namespace Queryr\WebApi\UseCases\GetItem;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GetItemRequest {

	private $itemId;

	public function __construct( string $itemId ) {
		$this->itemId = $itemId;
	}

	public function getItemId(): string {
		return $this->itemId;
	}
}