<?php

namespace Queryr\WebApi\UseCases\ListItems;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemList {

	private $elements;

	/**
	 * @param ItemListElement[] $itemListElements
	 */
	public function __construct( array $itemListElements ) {
		$this->elements = $itemListElements;
	}

	/**
	 * @return ItemListElement[]
	 */
	public function getElements(): array {
		return $this->elements;
	}

}
