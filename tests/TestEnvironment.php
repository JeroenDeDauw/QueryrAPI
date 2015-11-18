<?php

namespace Queryr\WebApi\Tests;

use Queryr\EntityStore\Data\EntityPageInfo;
use Queryr\EntityStore\Data\PropertyInfo;
use Queryr\EntityStore\Data\PropertyRow;
use Queryr\EntityStore\InstanceOfTypeExtractor;
use Queryr\EntityStore\ItemRowFactory;
use Queryr\WebApi\ApiFactory;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class TestEnvironment {

	public static function newInstance() {
		return new self();
	}

	/**
	 * @var ApiFactory
	 */
	private $factory;

	private function __construct() {
		$this->factory = ApiFactory::newFromConnectionData( [
			'driver' => 'pdo_sqlite',
			'memory' => true,
		] );

		$this->factory->newEntityStoreInstaller()->install();
		$this->factory->newTermStoreInstaller()->install();
	}

	public function getFactory(): ApiFactory {
		return $this->factory;
	}

	public function insertItem( Item $item ) {
		$this->storeItemInEntityStore( $item );
	}

	private function storeItemInEntityStore( Item $item ) {
		$rowFactory = new ItemRowFactory(
			$this->factory->getEntitySerializer(),
			new InstanceOfTypeExtractor()
		);

		$itemRow = $rowFactory->newFromItemAndPageInfo(
			$item,
			( new EntityPageInfo() )
				->setPageTitle( $item->getId()->getSerialization() )
				->setRevisionTime( '0000' )
				->setRevisionId( 0 )
		);

		$this->factory->getItemStore()->storeItemRow( $itemRow );
	}

	public function insertProperty( Property $property ) {
		$this->storePropertyInEntityStore( $property );
	}

	private function storePropertyInEntityStore( Property $property ) {
		$this->factory->getPropertyStore()->storePropertyRow(
			new PropertyRow(
				json_encode( $this->factory->getEntitySerializer()->serialize( $property ) ),
				new PropertyInfo(
					$property->getId()->getNumericId(),
					'Property:' . $property->getId()->getSerialization(),
					0,
					0,
					$property->getDataTypeId()
				)
			)
		);
	}

}
