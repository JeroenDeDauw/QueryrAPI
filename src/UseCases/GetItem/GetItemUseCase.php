<?php

declare(strict_types=1);

namespace Queryr\WebApi\UseCases\GetItem;

use Deserializers\Deserializer;
use Queryr\EntityStore\ItemStore;
use Queryr\WebApi\UseCases\GetItem\SimpleItemBuilder;
use Queryr\WebApi\ResponseModel\SimpleStatementsBuilder;
use Queryr\TermStore\LabelLookup;
use Queryr\WebApi\NoNullableReturnTypesException;
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

	public function __construct( ItemStore $itemStore, LabelLookup $labelLookup, Deserializer $itemDeserializer ) {
		$this->itemStore = $itemStore;
		$this->labelLookup = $labelLookup;
		$this->itemDeserializer = $itemDeserializer;
	}

	public function getItem( GetItemRequest $request ): SimpleItem {
		$itemJson = $this->getItemJson( $request->getItemId() );

		return $this->getSimpleItemFromItem(
			$this->itemDeserializer->deserialize( $itemJson ),
			$request->getLanguageCode()
		);
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
		$simpleItemBuilder = $this->newSimpleItemBuilder(
			$languageCode,
			$this->labelLookup
		);

		return $simpleItemBuilder->buildFromItem( $item );
	}

	private function newSimpleItemBuilder( $languageCode, LabelLookup $labelLookup ) {
		return new SimpleItemBuilder(
			$languageCode,
			new SimpleStatementsBuilder( $languageCode, $labelLookup )
		);
	}

}