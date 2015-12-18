<?php

namespace Queryr\WebApi\UseCases\ListProperties;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyList {

	private $elements;

	/**
	 * @param PropertyListElement[] $propertyListElements
	 */
	public function __construct( array $propertyListElements ) {
		$this->elements = $propertyListElements;
	}

	/**
	 * @return PropertyListElement[]
	 */
	public function getElements(): array {
		return $this->elements;
	}

}
