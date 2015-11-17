<?php

namespace Queryr\WebApi;

use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class UrlBuilder {

	private $apiUrl;
	private $wdUrl;

	public function __construct( $apiUrl ) {
		$this->apiUrl = $apiUrl;
		$this->wdUrl = 'https://www.wikidata.org';
	}

	public function getApiPath( string $path ): string {
		return $this->apiUrl . '/' . $path;
	}

	public function getWdEntityUrl( EntityId $id ): string {
		return $this->wdUrl . '/entity/' . $id->getSerialization();
	}

	public function getSiteLinkBasedRerirectUrl( string $siteId, ItemId $itemId ): string {
		return $this->wdUrl . '/wiki/Special:GoToLinkedPage/' . $siteId . '/' . $itemId->getSerialization();
	}

	public function getWdItemPageUrl( ItemId $id ): string {
		return $this->wdUrl . '/wiki/' . $id->getSerialization();
	}

	public function getWdPropertyPageUrl( PropertyId $id ): string {
		return $this->wdUrl . '/wiki/Property:' . $id->getSerialization();
	}

	public function getApiItemUrl( ItemId $id ): string {
		return $this->apiUrl . '/items/' . $id->getSerialization();
	}

	public function getApiPropertyUrl( PropertyId $id ): string {
		return $this->apiUrl . '/properties/' . $id->getSerialization();
	}

}
