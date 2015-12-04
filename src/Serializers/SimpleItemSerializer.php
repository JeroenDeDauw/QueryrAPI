<?php

namespace Queryr\WebApi\Serializers;

use Queryr\WebApi\UseCases\GetItem\SimpleItem;
use Serializers\Exceptions\UnsupportedObjectException;
use Serializers\Serializer;

/**
 * @access private
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimpleItemSerializer implements Serializer {

	/**
	 * @var Serializer
	 */
	private $entitySerializer;

	/**
	 * @var SimpleItem
	 */
	private $item;

	public function __construct( Serializer $simpleEntitySerializer ) {
		$this->entitySerializer = $simpleEntitySerializer;
	}

	public function serialize( $object ) {
		if ( !( $object instanceof SimpleItem ) ) {
			throw new UnsupportedObjectException( $object, 'Can only serialize instances of SimpleItem' );
		}

		$this->item = $object;

		return $this->serializeItem();
	}

	private function serializeItem(): array {
		$serialization = $this->entitySerializer->serialize( $this->item );

		return $serialization;
	}

}