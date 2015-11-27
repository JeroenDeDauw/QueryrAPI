<?php

namespace Queryr\Resources;

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

	/**
	 * @param PropertyId $propertyId
	 * @param string $propertyType
	 * @param string $pageUrl
	 * @param string $apiUrl
	 */
	public function __construct( PropertyId $propertyId, $propertyType, $pageUrl, $apiUrl ) {
		$this->propertyId = $propertyId;
		$this->propertyType = $propertyType;
		$this->pageUrl = $pageUrl;
		$this->apiUrl = $apiUrl;
	}

	/**
	 * @return string
	 */
	public function getApiUrl() {
		return $this->apiUrl;
	}

	/**
	 * @return string
	 */
	public function getWikidataUrl() {
		return $this->pageUrl;
	}

	/**
	 * @return PropertyId
	 */
	public function getPropertyId() {
		return $this->propertyId;
	}

	/**
	 * @return string
	 */
	public function getPropertyType() {
		return $this->propertyType;
	}

}
