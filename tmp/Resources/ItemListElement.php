<?php

namespace Queryr\Resources;

use InvalidArgumentException;
use RuntimeException;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemListElement {

	private $itemId;
	private $label;
	private $lastUpdate;
	private $queryrApiUrl;
	private $wikidataPageUrl;
	private $wikipediaPageUrl;

	/**
	 * @param ItemId $itemId
	 * @return $this
	 */
	public function setItemId( ItemId $itemId ) {
		$this->itemId = $itemId;
		return $this;
	}

	/**
	 * @param string|null $label
	 * @return $this
	 * @throws InvalidArgumentException
	 */
	public function setLabel( $label ) {
		if ( !is_string( $label ) && $label !== null ) {
			throw new InvalidArgumentException( '$label needs to be string or null' );
		}

		$this->label = $label;
		return $this;
	}

	/**
	 * @param string $lastUpdate
	 * @return $this
	 * @throws InvalidArgumentException
	 */
	public function setLastUpdate( $lastUpdate ) {
		if ( !is_string( $lastUpdate ) ) {
			throw new InvalidArgumentException( '$wikidataPageUrl needs to be a string' );
		}

		$this->lastUpdate = $lastUpdate;
		return $this;
	}

	/**
	 * @param string $queryrApiUrl
	 * @return $this
	 * @throws InvalidArgumentException
	 */
	public function setQueryrApiUrl( $queryrApiUrl ) {
		if ( !is_string( $queryrApiUrl ) ) {
			throw new InvalidArgumentException( '$wikidataPageUrl needs to be a string' );
		}

		$this->queryrApiUrl = $queryrApiUrl;
		return $this;
	}

	/**
	 * @param string $wikidataPageUrl
	 * @return $this
	 * @throws InvalidArgumentException
	 */
	public function setWikidataPageUrl( $wikidataPageUrl ) {
		if ( !is_string( $wikidataPageUrl ) ) {
			throw new InvalidArgumentException( '$wikidataPageUrl needs to be a string' );
		}

		$this->wikidataPageUrl = $wikidataPageUrl;
		return $this;
	}

	/**
	 * @param string|null $wikipediaPageUrl
	 * @return $this
	 * @throws InvalidArgumentException
	 */
	public function setWikipediaPageUrl( $wikipediaPageUrl ) {
		if ( !is_string( $wikipediaPageUrl ) && $wikipediaPageUrl !== null ) {
			throw new InvalidArgumentException( '$wikipediaPageUrl needs to be string or null' );
		}

		$this->wikipediaPageUrl = $wikipediaPageUrl;
		return $this;
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
	public function getLastUpdateTime() {
		if ( $this->lastUpdate === null ) {
			throw new RuntimeException( 'Field not set' );
		}
		return $this->lastUpdate;
	}

	/**
	 * @return string|null
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @return string
	 * @throws RuntimeException
	 */
	public function getQueryrApiUrl() {
		if ( $this->queryrApiUrl === null ) {
			throw new RuntimeException( 'Field not set' );
		}
		return $this->queryrApiUrl;
	}

	/**
	 * @return string
	 * @throws RuntimeException
	 */
	public function getWikidataUrl() {
		if ( $this->wikidataPageUrl === null ) {
			throw new RuntimeException( 'Field not set' );
		}
		return $this->wikidataPageUrl;
	}

	/**
	 * @return string|null
	 */
	public function getWikipediaPageUrl() {
		return $this->wikipediaPageUrl;
	}

}
