<?php

namespace Queryr\WebApi\UseCases\ListProperties;

use Wikibase\DataModel\Entity\PropertyId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyListElement {

	private $propertyId;
	private $propertyType;
	private $pageUrl;
	private $apiUrl;

	public function __construct( PropertyId $propertyId, string $propertyType, string $pageUrl, string $apiUrl ) {
		$this->propertyId = $propertyId;
		$this->propertyType = $propertyType;
		$this->pageUrl = $pageUrl;
		$this->apiUrl = $apiUrl;
	}

	public function getApiUrl(): string {
		return $this->apiUrl;
	}

	public function getWikidataUrl(): string {
		return $this->pageUrl;
	}

	public function getPropertyId(): PropertyId {
		return $this->propertyId;
	}

	public function getPropertyType(): string {
		return $this->propertyType;
	}

}
