<?php

declare(strict_types=1);

namespace Queryr\WebApi\UseCases\GetProperty;

use Deserializers\Deserializer;
use Queryr\EntityStore\PropertyStore;
use Queryr\WebApi\UseCases\GetProperty\SimplePropertyBuilder;
use Queryr\WebApi\UseCases\GetProperty\SimpleProperty;
use Queryr\WebApi\NoNullableReturnTypesException;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GetPropertyUseCase {

	private $propertyStore;
	private $propertyDeserializer;

	public function __construct( PropertyStore $propertyStore, Deserializer $propertyDeserializer ) {
		$this->propertyStore = $propertyStore;
		$this->propertyDeserializer = $propertyDeserializer;
	}

	public function getProperty( GetPropertyRequest $request ): SimpleProperty {
		$propertyJson = $this->getPropertyJson( $request->getPropertyId() );
		$simplePropertyBuilder = new SimplePropertyBuilder( $request->getLanguageCode() );
		return $simplePropertyBuilder->buildFromProperty( $this->propertyDeserializer->deserialize( $propertyJson ) );
	}

	private function getPropertyJson( string $id ): array {
		// TODO: handle id exception
		// https://groups.google.com/forum/#!topic/clean-code-discussion/GcQNqWG_fuo
		$id = new PropertyId( $id );
		$propertyRow = $this->propertyStore->getPropertyRowByNumericPropertyId( $id->getNumericId() );

		if ( $propertyRow === null ) {
			throw new NoNullableReturnTypesException();
		}

		return json_decode( $propertyRow->getPropertyJson(), true );
	}

}