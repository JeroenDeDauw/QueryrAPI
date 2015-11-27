<?php

namespace Queryr\WebApi\UseCases\ListProperties;

use Queryr\WebApi\UseCases\ListProperties\PropertyListElement;

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
	public function getElements() {
		return $this->elements;
	}

}
