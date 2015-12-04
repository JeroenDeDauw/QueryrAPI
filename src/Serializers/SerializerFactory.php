<?php

namespace Queryr\WebApi\Serializers;

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
			new SimpleEntitySerializer( new SimpleStatementSerializer() )

		);
	}

	/**
	 * @return Serializer
	 */
	public function newSimplePropertySerializer() {
		return new SimplePropertySerializer(
			new SimpleEntitySerializer( new SimpleStatementSerializer() )
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
