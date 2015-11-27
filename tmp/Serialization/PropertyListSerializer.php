<?php

namespace Queryr\Serialization;

use Queryr\Resources\PropertyList;
use Queryr\Resources\PropertyListElement;
use Serializers\Exceptions\UnsupportedObjectException;
use Serializers\Serializer;

/**
 * @access private
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyListSerializer implements Serializer {

	public function serialize( $object ) {
		if ( !( $object instanceof PropertyList ) ) {
			throw new UnsupportedObjectException( $object, 'Can only serialize instances of PropertyList' );
		}

		return $this->serializeList( $object );
	}

	private function serializeList( PropertyList $list ) {
		$serialization = [];

		foreach ( $list->getElements() as $element ) {
			$serialization[] = $this->serializeElement( $element );
		}

		return $serialization;
	}

	private function serializeElement( PropertyListElement $element ) {
		return [
			'id' => $element->getPropertyId()->getSerialization(),
			'type' => $element->getPropertyType(),
			'url' => $element->getApiUrl(),
			'wikidata_url' => $element->getWikidataUrl(),
		];
	}

}
