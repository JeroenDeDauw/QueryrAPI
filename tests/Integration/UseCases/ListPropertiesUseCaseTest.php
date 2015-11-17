<?php

namespace Queryr\WebApi\Tests\Integration\UseCases;

use Queryr\EntityStore\Data\PropertyInfo;
use Queryr\EntityStore\Data\PropertyRow;
use Queryr\Resources\PropertyList;
use Queryr\Resources\PropertyListElement;
use Queryr\WebApi\ApiFactory;
use Queryr\WebApi\Tests\TestEnvironment;
use Queryr\WebApi\UseCases\ListProperties\PropertyListingRequest;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @covers Queryr\WebApi\UseCases\ListProperties\ListPropertiesUseCase
 * @covers Queryr\WebApi\UseCases\ListProperties\PropertyListingRequest
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ListPropertiesUseCaseTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var ApiFactory
	 */
	private $apiFactory;

	public function setUp() {
		$this->apiFactory = TestEnvironment::newInstance()->getFactory();
	}

	public function testWhenNoProperties_emptyListIsReturned() {
		$useCase = $this->apiFactory->newListPropertiesUseCase();

		$request = new PropertyListingRequest();
		$request->setPerPage( 100 );
		$request->setPage( 1 );

		$this->assertEquals(
			new PropertyList( [] ),
			$useCase->listProperties( $request )
		);
	}

	private function storeThreeProperties() {
		$store = $this->apiFactory->getPropertyStore();

		$store->storePropertyRow( new PropertyRow(
			'',
			new PropertyInfo(
				1,
				'Property:P1',
				0,
				'2015-11-16T20:43:11Z',
				'wikibase-item'
			)
		) );

		$store->storePropertyRow( new PropertyRow(
			'',
			new PropertyInfo(
				2,
				'Property:P2',
				0,
				'2015-11-16T20:43:22Z',
				'commonsMedia'
			)
		) );

		$store->storePropertyRow( new PropertyRow(
			'',
			new PropertyInfo(
				3,
				'Property:P3',
				0,
				'2015-11-16T20:43:33Z',
				'wikibase-item'
			)
		) );
	}

	public function testWhenThreeProperties_theyAreAllReturned() {
		$this->storeThreeProperties();

		$request = new PropertyListingRequest();
		$request->setPerPage( 100 );
		$request->setPage( 1 );

		$this->assertEquals(
			new PropertyList( [
				new PropertyListElement(
					new PropertyId( 'P1' ),
					'wikibase-item',
					'https://www.wikidata.org/entity/P1',
					'http://test.url/properties/P1'
				),
				new PropertyListElement(
					new PropertyId( 'P2' ),
					'commonsMedia',
					'https://www.wikidata.org/entity/P2',
					'http://test.url/properties/P2'
				),
				new PropertyListElement(
					new PropertyId( 'P3' ),
					'wikibase-item',
					'https://www.wikidata.org/entity/P3',
					'http://test.url/properties/P3'
				),
			] ),
			$this->apiFactory->newListPropertiesUseCase()->listProperties( $request )
		);
	}

	public function testLimitIsApplied() {
		$this->storeThreeProperties();

		$request = new PropertyListingRequest();
		$request->setPerPage( 1 );
		$request->setPage( 1 );

		$this->assertEquals(
			new PropertyList( [
				new PropertyListElement(
					new PropertyId( 'P1' ),
					'wikibase-item',
					'https://www.wikidata.org/entity/P1',
					'http://test.url/properties/P1'
				),
			] ),
			$this->apiFactory->newListPropertiesUseCase()->listProperties( $request )
		);
	}

	public function testOffsetIsApplied() {
		$this->storeThreeProperties();

		$request = new PropertyListingRequest();
		$request->setPerPage( 1 );
		$request->setPage( 2 );

		$this->assertEquals(
			new PropertyList( [
				new PropertyListElement(
					new PropertyId( 'P2' ),
					'commonsMedia',
					'https://www.wikidata.org/entity/P2',
					'http://test.url/properties/P2'
				),
			] ),
			$this->apiFactory->newListPropertiesUseCase()->listProperties( $request )
		);
	}

}
