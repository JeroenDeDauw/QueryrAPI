<?php

namespace Queryr\Resources;

use DataValues\DataValue;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimpleStatement {

	/**
	 * @var string
	 */
	public $propertyName;

	/**
	 * @var PropertyId
	 */
	public $propertyId;

	/**
	 * @var string
	 */
	public $valueType;

	/**
	 * Should have at least one element.
	 *
	 * @var DataValue[]
	 */
	public $values = [];

	public static function newInstance() {
		return new self();
	}

	public function withPropertyName( $propertyName ) {
		$this->propertyName = $propertyName;
		return $this;
	}

	public function withType( $type ) {
		$this->valueType = $type;
		return $this;
	}

	public function withValues( array $values ) {
		$this->values = $values;
		return $this;
	}

	public function withPropertyId( $propertyId ) {
		$this->propertyId = is_string( $propertyId ) ? new PropertyId( $propertyId ) : $propertyId;
		return $this;
	}

}