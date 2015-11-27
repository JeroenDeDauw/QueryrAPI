<?php

namespace Queryr\Serialization;

use Queryr\Resources\SimpleItem;
use Queryr\Resources\SimpleProperty;
use Serializers\Exceptions\UnsupportedObjectException;
use Serializers\Serializer;

/**
 * @access private
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimpleFoundationSerializer implements Serializer {

	/**
	 * @var SimpleItem|SimpleProperty
	 */
	private $entity;

	public function serialize( $object ) {
		if ( !( $object instanceof SimpleItem ) && !( $object instanceof SimpleProperty ) ) {
			throw new UnsupportedObjectException( $object, 'Can only serialize instances of SimpleItem or SimpleProperty' );
		}

		$this->entity = $object;

		return $this->serializeItem();
	}

	private function serializeItem() {
		$serialization = [ 'id' => $this->entity->ids ];

		if ( $this->entity->label !== '' ) {
			$serialization['label'] = $this->entity->label;
		}

		if ( $this->entity->description !== '' ) {
			$serialization['description'] = $this->entity->description;
		}

		if ( !empty( $this->entity->aliases ) ) {
			$serialization['aliases'] = $this->entity->aliases;
		}

		return $serialization;
	}

}