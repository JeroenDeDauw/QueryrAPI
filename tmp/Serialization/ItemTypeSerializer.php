<?php

namespace Queryr\Serialization;

use Queryr\Resources\ItemType;
use Serializers\Exceptions\UnsupportedObjectException;
use Serializers\Serializer;

/**
 * @access private
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemTypeSerializer implements Serializer {

	public function serialize( $object ) {
		if ( !( $object instanceof ItemType ) ) {
			throw new UnsupportedObjectException( $object, 'Can only serialize instances of ItemType' );
		}

		return $this->serializeItemType( $object );
	}

	private function serializeItemType( ItemType $itemType ) {
		return [
			'label' => $itemType->getLabel(),
			'id' => $itemType->getItemId()->getSerialization(),
			'url' => $itemType->getApiUrl(),
			'wikidata_url' => $itemType->getWikidataUrl(),
		];
	}

}
