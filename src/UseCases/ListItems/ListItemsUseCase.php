<?php

declare(strict_types=1);

namespace Queryr\WebApi\UseCases\ListItems;

use Queryr\EntityStore\Data\ItemInfo;
use Queryr\EntityStore\ItemStore;
use Queryr\WebApi\UrlBuilder;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ListItemsUseCase {

	private $itemStore;
	private $urlBuilder;

	public function __construct( ItemStore $itemStore, UrlBuilder $urlBuilder ) {
		$this->itemStore = $itemStore;
		$this->urlBuilder = $urlBuilder;
	}

	public function listItems( ItemListingRequest $request ): ItemList {
		$itemList = [];

		foreach ( $this->getItemInfo( $request ) as $itemInfo ) {
			$itemList[] = $this->itemInfoToItemListElement( $itemInfo );
		}

		return new ItemList( $itemList );
	}

	private function getItemInfo( ItemListingRequest $request ) {
		return $this->itemStore->getItemInfo(
			$request->getPerPage(),
			( $request->getPage() - 1 ) * $request->getPerPage()
		);
	}

	private function itemInfoToItemListElement( ItemInfo $itemInfo ): ItemListElement {
		$id = ItemId::newFromNumber( $itemInfo->getNumericItemId() );

		return ( new ItemListElement() )
				->setItemId( $id )
				->setLabel( $itemInfo->getEnglishLabel() )
				->setLastUpdate( $itemInfo->getRevisionTime() )
				->setQueryrApiUrl( $this->urlBuilder->getApiItemUrl( $id ) )
				->setWikidataPageUrl( $this->urlBuilder->getWdEntityUrl( $id ) )
				->setWikipediaPageUrl( $this->urlBuilder->getSiteLinkBasedRerirectUrl( 'enwiki', $id ) );
	}

}