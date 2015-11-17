<?php

declare(strict_types=1);

namespace Queryr\WebApi\UseCases\ListProperties;

use Queryr\EntityStore\Data\PropertyInfo;
use Queryr\EntityStore\PropertyStore;
use Queryr\Resources\PropertyList;
use Queryr\Resources\PropertyListElement;
use Queryr\WebApi\UrlBuilder;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ListPropertiesUseCase {

	private $propertyStore;
	private $urlBuilder;

	public function __construct( PropertyStore $propertyStore, UrlBuilder $urlBuilder ) {
		$this->propertyStore = $propertyStore;
		$this->urlBuilder = $urlBuilder;
	}

	public function listProperties( PropertyListingRequest $request ): PropertyList {
		$propertyList = [];

		foreach ( $this->getPropertyInfo( $request ) as $propertyInfo ) {
			$propertyList[] = $this->propertyInfoToPropertyListElement( $propertyInfo );
		}

		return new PropertyList( $propertyList );
	}

	private function getPropertyInfo( PropertyListingRequest $request ) {
		return $this->propertyStore->getPropertyInfo(
			$request->getPerPage(),
			( $request->getPage() - 1 ) * $request->getPerPage()
		);
	}

	private function propertyInfoToPropertyListElement( PropertyInfo $propertyInfo ): PropertyListElement {
		$id = PropertyId::newFromNumber( $propertyInfo->getNumericPropertyId() );

		return new PropertyListElement(
			$id,
			$propertyInfo->getPropertyType(),
			$this->urlBuilder->getWdEntityUrl( $id ),
			$this->urlBuilder->getApiPropertyUrl( $id )
		);
	}

}