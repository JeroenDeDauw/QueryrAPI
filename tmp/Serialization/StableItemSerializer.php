<?php

namespace Queryr\Serialization;

use Queryr\Resources\SimpleItem;
use Serializers\Exceptions\UnsupportedObjectException;
use Serializers\Serializer;

/**
 * @access private
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class StableItemSerializer implements Serializer {

	private $propertyMap;

	/**
	 * @var Serializer
	 */
	private $foundationalSerializer;

	/**
	 * @var Serializer
	 */
	private $statementSerializer;

	/**
	 * @var SimpleItem
	 */
	private $item;

	/**
	 * @param Serializer $foundationalSerializer
	 * @param Serializer $statementSerializer
	 * @param string[] $propertyMap Maps property id (string) to stable property name
	 */
	public function __construct( Serializer $foundationalSerializer, Serializer $statementSerializer, array $propertyMap ) {
		$this->propertyMap = $propertyMap;
		$this->foundationalSerializer = $foundationalSerializer;
		$this->statementSerializer = $statementSerializer;
	}

	public function serialize( $object ) {
		if ( !( $object instanceof SimpleItem ) ) {
			throw new UnsupportedObjectException( $object, 'Can only serialize instances of SimpleItem' );
		}

		$this->item = $object;

		return $this->serializeItem();
	}

	private function serializeItem() {
		$serialization = $this->foundationalSerializer->serialize( $this->item );

		$serialization['data'] = $this->getDataSection();

		return $serialization;
	}

	private function getDataSection() {
		$data = [];

		foreach ( $this->item->statements as $simpleStatement ) {
			$propertyId = $simpleStatement->propertyId->getSerialization();

			if ( array_key_exists( $propertyId, $this->propertyMap ) ) {
				$data[$this->propertyMap[$propertyId]] = $this->statementSerializer->serialize( $simpleStatement );
			}
		}

		return $data;
	}

}
