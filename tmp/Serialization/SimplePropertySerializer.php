<?php

namespace Queryr\Serialization;

use Queryr\Resources\SimpleProperty;
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
	private $foundationalSerializer;

	/**
	 * @var SimpleProperty
	 */
	private $property;

	public function __construct( Serializer $foundationalSerializer ) {
		$this->foundationalSerializer = $foundationalSerializer;
	}

	public function serialize( $object ) {
		if ( !( $object instanceof SimpleProperty ) ) {
			throw new UnsupportedObjectException( $object, 'Can only serialize instances of SimpleProperty' );
		}

		$this->property = $object;

		return $this->serializeProperty();
	}

	private function serializeProperty() {
		$serialization = $this->foundationalSerializer->serialize( $this->property );

		$serialization['type'] = $this->property->type;

		return $serialization;
	}

}