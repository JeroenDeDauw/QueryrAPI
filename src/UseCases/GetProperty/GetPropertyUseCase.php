<?php

declare(strict_types=1);

namespace Queryr\WebApi\UseCases\GetProperty;

use Deserializers\Deserializer;
use OhMyPhp\NoNullableReturnTypesException;
use Queryr\EntityStore\PropertyStore;
use Queryr\TermStore\LabelLookup;
use Queryr\WebApi\ResponseModel\SimpleStatement;
use Queryr\WebApi\ResponseModel\SimpleStatementsBuilder;
use Queryr\WebApi\UrlBuilder;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GetPropertyUseCase {

	private $propertyStore;
	private $propertyDeserializer;
	private $urlBuilder;
	private $labelLookup;

	public function __construct( PropertyStore $propertyStore, LabelLookup $labelLookup,
			Deserializer $propertyDeserializer, UrlBuilder $urlBuilder ) {

		$this->propertyStore = $propertyStore;
		$this->labelLookup = $labelLookup;
		$this->propertyDeserializer = $propertyDeserializer;
		$this->urlBuilder = $urlBuilder;
	}

	public function getProperty( GetPropertyRequest $request ): SimpleProperty {
		$propertyJson = $this->getPropertyJson( $request->getPropertyId() );

		$simplePropertyBuilder = $this->newSimplePropertyBuilder( $request->getLanguageCode() );

		$property = $simplePropertyBuilder->buildFromProperty( $this->propertyDeserializer->deserialize( $propertyJson ) );

		usort( $property->statements, function( SimpleStatement $s0, SimpleStatement $s1 ) {
			return $s0->propertyId->getNumericId() <=> $s1->propertyId->getNumericId();
		} );

		return $property;
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

	private function newSimplePropertyBuilder( $languageCode ) {
		return new SimplePropertyBuilder(
			$languageCode,
			new SimpleStatementsBuilder( $languageCode, $this->labelLookup, $this->urlBuilder ),
			$this->urlBuilder
		);
	}

}