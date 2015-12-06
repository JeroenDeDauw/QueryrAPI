<?php

namespace Queryr\WebApi\Serializers;

use Queryr\WebApi\ResponseModel\SimpleStatement;
use Serializers\Exceptions\UnsupportedObjectException;
use Serializers\Serializer;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @access private
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimpleStatementSerializer implements Serializer {

	public function serialize( $simpleStatement ) {
		if ( !( $simpleStatement instanceof SimpleStatement ) ) {
			throw new UnsupportedObjectException( $simpleStatement, 'Can only serialize instances of SimpleStatement' );
		}

		// TODO: verify $simpleStatement

		$propertyValue = [
			'property' => $this->getPropertySerialization( $simpleStatement ),
			'value' => $simpleStatement->values[0]->getArrayValue(),
			'type' => $simpleStatement->valueType
		];

		if ( count( $simpleStatement->values ) > 1 ) {
			$propertyValue['values'] = [];

			foreach ( $simpleStatement->values as $value ) {
				$propertyValue['values'][] = $value->getArrayValue();
			}
		}

		return $propertyValue;
	}

	private function getPropertySerialization( SimpleStatement $simpleStatement ) {
		return [
			'label' => $simpleStatement->propertyName,
			'id' => $simpleStatement->propertyId->getSerialization(),
			'url' => $simpleStatement->propertyUrl,
		];
	}

}