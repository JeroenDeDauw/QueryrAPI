<?php

namespace Queryr\Serialization;

use Serializers\Serializer;

/**
 * @access public
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SerializerFactory {

	/**
	 * @return Serializer
	 */
	public function newSimpleItemSerializer() {
		return new SimpleItemSerializer(
			new SimpleFoundationSerializer(),
			new SimpleStatementSerializer()
		);
	}

	/**
	 * @return Serializer
	 */
	public function newSimplePropertySerializer() {
		return new SimplePropertySerializer(
			new SimpleFoundationSerializer()
		);
	}

	/**
	 * @param string[] $propertyMap Maps property id (string) to stable property name
	 * @return Serializer
	 */
	public function newStableItemSerializer( array $propertyMap ) {
		return new StableItemSerializer(
			new SimpleFoundationSerializer(),
			new SimpleStatementSerializer(),
			$propertyMap
		);
	}

	/**
	 * @return Serializer
	 */
	public function newPropertyListSerializer() {
		return new PropertyListSerializer();
	}

	/**
	 * @return Serializer
	 */
	public function newItemListSerializer() {
		return new ItemListSerializer();
	}

	/**
	 * @return Serializer
	 */
	public function newItemTypeSerializer() {
		return new ItemTypeSerializer();
	}

}
