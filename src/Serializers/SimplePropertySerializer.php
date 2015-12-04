<?php

namespace Queryr\WebApi\Serializers;

use Queryr\WebApi\UseCases\GetProperty\SimpleProperty;
use Serializers\Exceptions\UnsupportedObjectException;
use Serializers\Serializer;

/**
 * @access private
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimplePropertySerializer implements Serializer {

	/**
	 * @var Serializer
	 */
	private $entitySerializer;

	/**
	 * @var SimpleProperty
	 */
	private $property;

	public function __construct( Serializer $simpleEntitySerializer ) {
		$this->entitySerializer = $simpleEntitySerializer;
	}

	public function serialize( $object ) {
		if ( !( $object instanceof SimpleProperty ) ) {
			throw new UnsupportedObjectException( $object, 'Can only serialize instances of SimpleProperty' );
		}

		$this->property = $object;

		return $this->serializeProperty();
	}

	private function serializeProperty(): array {
		return array_merge(
			[ 'type' => $this->property->type ],
			$this->entitySerializer->serialize( $this->property )
		);
	}

}