<?php

namespace Queryr\WebApi\ResponseModel;

use DataValues\DataValue;
use OhMyPhp\ValueObjectsInPhpStuckBalls;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimpleStatement {
	use ValueObjectsInPhpStuckBalls;

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
	public $propertyUrl;

	/**
	 * @var string
	 */
	public $valueType;

	/**
	 * Should have at least one element.
	 *
	 * @var DataValue[]
	 */
	public $values;

	public function withPropertyName( string $propertyName ): self {
		$this->propertyName = $propertyName;
		return $this;
	}

	public function withType( string $type ): self {
		$this->valueType = $type;
		return $this;
	}

	public function withValues( array $values ): self {
		$this->values = $values;
		return $this;
	}

	public function withPropertyId( PropertyId $propertyId ): self {
		$this->propertyId = $propertyId;
		return $this;
	}

	public function withPropertyUrl( string $propertyLabel ): self {
		$this->propertyUrl = $propertyLabel;
		return $this;
	}

}
