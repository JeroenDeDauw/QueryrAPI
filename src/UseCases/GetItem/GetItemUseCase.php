<?php

declare(strict_types=1);

namespace Queryr\WebApi\UseCases\GetItem;

use Deserializers\Deserializer;
use OhMyPhp\NoNullableReturnTypesException;
use Queryr\EntityStore\ItemStore;
use Queryr\TermStore\LabelLookup;
use Queryr\WebApi\ResponseModel\SimpleStatement;
use Queryr\WebApi\ResponseModel\SimpleStatementsBuilder;
use Queryr\WebApi\UrlBuilder;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class GetItemUseCase {

	private $itemStore;
	private $labelLookup;
	private $itemDeserializer;
	private $urlBuilder;

	public function __construct( ItemStore $itemStore, LabelLookup $labelLookup,
			Deserializer $itemDeserializer, UrlBuilder $urlBuilder ) {

		$this->itemStore = $itemStore;
		$this->labelLookup = $labelLookup;
		$this->itemDeserializer = $itemDeserializer;
		$this->urlBuilder = $urlBuilder;
	}

	public function getItem( GetItemRequest $request ): SimpleItem {
		$itemJson = $this->getItemJson( $request->getItemId() );

		$item = $this->getSimpleItemFromItem(
			$this->itemDeserializer->deserialize( $itemJson ),
			$request->getLanguageCode()
		);

		usort( $item->statements, function( SimpleStatement $s0, SimpleStatement $s1 ) {
			return $s0->propertyId->getNumericId() <=> $s1->propertyId->getNumericId();
		} );

		return $item;
	}

	private function getItemJson( string $id ): array {
		// TODO: handle id exception
		// https://groups.google.com/forum/#!topic/clean-code-discussion/GcQNqWG_fuo
		$id = new ItemId( $id );
		$itemRow = $this->itemStore->getItemRowByNumericItemId( $id->getNumericId() );

		if ( $itemRow === null ) {
			throw new NoNullableReturnTypesException();
		}

		return json_decode( $itemRow->getItemJson(), true );
	}

	private function getSimpleItemFromItem( Item $item, string $languageCode ): SimpleItem {
		return $this->newSimpleItemBuilder( $languageCode )->buildFromItem( $item );
	}

	private function newSimpleItemBuilder( $languageCode ) {
		return new SimpleItemBuilder(
			$languageCode,
			new SimpleStatementsBuilder( $languageCode, $this->labelLookup, $this->urlBuilder ),
			$this->urlBuilder
		);
	}

}