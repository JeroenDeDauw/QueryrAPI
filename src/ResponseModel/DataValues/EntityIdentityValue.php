<?php

namespace Queryr\WebApi\ResponseModel\DataValues;

use DataValues\DataValueObject;
use Wikibase\DataModel\Entity\EntityId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntityIdentityValue extends DataValueObject {

	private $id;
	private $label;
	private $url;

	public function __construct( EntityId $id, string $label, string $url ) {
		$this->id = $id;
		$this->label = $label;
		$this->url = $url;
	}

	/**
	 * @see DataValue::getType
	 *
	 * @return string
	 */
	public static function getType() {
		return 'queryr-entity-identity';
	}

	/**
	 * @see DataValue::getArrayValue
	 *
	 * @return string[]
	 */
	public function getArrayValue() {
		return [
			'label' => $this->label,
			'id' => $this->id->getSerialization(),
			'url' => $this->url,
		];
	}

	/**
	 * @see DataValue::getSortKey
	 *
	 * @return string|float|int
	 */
	public function getSortKey() {
		return $this->id->getSerialization();
	}

	/**
	 * @see DataValue::getValue
	 *
	 * @since 0.5
	 *
	 * @return self
	 */
	public function getValue() {
		return $this;
	}

	public function serialize() {
		throw new \RuntimeException( 'Not implemented' );
	}

	public function unserialize( $value ) {
		throw new \RuntimeException( 'Not implemented' );
	}

}
