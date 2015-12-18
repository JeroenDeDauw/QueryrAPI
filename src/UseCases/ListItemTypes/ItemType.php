<?php

namespace Queryr\WebApi\UseCases\ListItemTypes;

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

	public function setApiUrl( string $apiUrl ) {
		$this->apiUrl = $apiUrl;
	}

	public function setItemId( ItemId $itemId ) {
		$this->itemId = $itemId;
	}

	public function setLabel( string $label ) {
		$this->label = $label;
	}

	public function setWikidataUrl( string $wikidataUrl ) {
		$this->wikidataUrl = $wikidataUrl;
	}

	/**
	 * @throws RuntimeException
	 */
	public function getApiUrl(): string {
		if ( $this->apiUrl === null ) {
			throw new RuntimeException( 'Field not set' );
		}
		return $this->apiUrl;
	}

	/**
	 * @throws RuntimeException
	 */
	public function getItemId(): ItemId {
		if ( $this->itemId === null ) {
			throw new RuntimeException( 'Field not set' );
		}
		return $this->itemId;
	}

	/**
	 * @throws RuntimeException
	 */
	public function getLabel(): string {
		if ( $this->label === null ) {
			throw new RuntimeException( 'Field not set' );
		}
		return $this->label;
	}

	/**
	 * @throws RuntimeException
	 */
	public function getWikidataUrl(): string {
		if ( $this->wikidataUrl === null ) {
			throw new RuntimeException( 'Field not set' );
		}
		return $this->wikidataUrl;
	}

}
