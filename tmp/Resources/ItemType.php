<?php

namespace Queryr\Resources;

use RuntimeException;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemType {

	private $label;
	private $itemId;
	private $apiUrl;
	private $wikidataUrl;

	/**
	 * @param string $apiUrl
	 */
	public function setApiUrl( $apiUrl ) {
		$this->apiUrl = $apiUrl;
	}

	/**
	 * @param ItemId $itemId
	 */
	public function setItemId( ItemId $itemId ) {
		$this->itemId = $itemId;
	}

	/**
	 * @param string $label
	 */
	public function setLabel( $label ) {
		$this->label = $label;
	}

	/**
	 * @param string $wikidataUrl
	 */
	public function setWikidataUrl( $wikidataUrl ) {
		$this->wikidataUrl = $wikidataUrl;
	}

	/**
	 * @return string
	 * @throws RuntimeException
	 */
	public function getApiUrl() {
		if ( $this->apiUrl === null ) {
			throw new RuntimeException( 'Field not set' );
		}
		return $this->apiUrl;
	}

	/**
	 * @return ItemId
	 * @throws RuntimeException
	 */
	public function getItemId() {
		if ( $this->itemId === null ) {
			throw new RuntimeException( 'Field not set' );
		}
		return $this->itemId;
	}

	/**
	 * @return string
	 * @throws RuntimeException
	 */
	public function getLabel() {
		if ( $this->label === null ) {
			throw new RuntimeException( 'Field not set' );
		}
		return $this->label;
	}

	/**
	 * @return string
	 * @throws RuntimeException
	 */
	public function getWikidataUrl() {
		if ( $this->wikidataUrl === null ) {
			throw new RuntimeException( 'Field not set' );
		}
		return $this->wikidataUrl;
	}

}
