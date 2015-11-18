<?php

declare(strict_types=1);

namespace Queryr\WebApi\UseCases\GetItem;

use Deserializers\Deserializer;
use Queryr\EntityStore\ItemStore;
use Queryr\Resources\Builders\BuilderFactory;
use Queryr\Resources\Builders\ResourceLabelLookup;
use Queryr\Resources\SimpleItem;
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
	private $entityDeserializer;

	public function __construct( ItemStore $itemStore, ResourceLabelLookup $labelLookup, Deserializer $entityDeserializer ) {
		$this->itemStore = $itemStore;
		$this->labelLookup = $labelLookup;
		$this->entityDeserializer = $entityDeserializer;
	}

	public function getItem( GetItemRequest $request ): SimpleItem {
		$itemJson = $this->getItemJson( $request->getItemId() );

		return $this->getSimpleItemFromItem(
			$this->entityDeserializer->deserialize( $itemJson ),
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
		$simpleItemBuilder = ( new BuilderFactory() )->newSimpleItemBuilder(
			$languageCode,
			$this->labelLookup
		);

		return $simpleItemBuilder->buildFromItem( $item );
	}

}