<?php

declare(strict_types=1);

namespace Queryr\WebApi\UseCases\ListItemTypes;

use Queryr\EntityStore\ItemStore;
use Queryr\WebApi\UseCases\ListItemTypes\ItemType;
use Queryr\TermStore\LabelLookup;
use Queryr\WebApi\UrlBuilder;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ListItemTypesUseCase {

	private $itemStore;
	private $labelLookup;
	private $urlBuilder;

	public function __construct( ItemStore $itemStore, LabelLookup $labelLookup, UrlBuilder $urlBuilder ) {
		$this->itemStore = $itemStore;
		$this->labelLookup = $labelLookup;
		$this->urlBuilder = $urlBuilder;
	}

	/**
	 * @param ItemTypesListingRequest $request
	 *
	 * @return ItemType[]
	 */
	public function listItemTypes( ItemTypesListingRequest $request ): array {
		return array_map(
			function( ItemId $id ) use ( $request ): ItemType {
				$itemType = new ItemType();
				$itemType->setItemId( $id );
				$itemType->setApiUrl( $this->urlBuilder->getApiItemUrl( $id ) );
				$itemType->setWikidataUrl( $this->urlBuilder->getWdEntityUrl( $id ) );

				$label = $this->labelLookup->getLabelByIdAndLanguage( $id, $request->getLanguageCode() );
				$itemType->setLabel( $label === null ? $id->getSerialization() : $label );

				return $itemType;
			},
			array_map(
				'\Wikibase\DataModel\Entity\ItemId::newFromNumber',
				$this->itemStore->getItemTypes(
					$request->getPerPage(),
					( $request->getPage() - 1 ) * $request->getPerPage()
				)
			)
		);
	}

}