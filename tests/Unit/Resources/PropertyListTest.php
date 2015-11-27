<?php

namespace Tests\Queryr\Resources\Builders;

use Queryr\Resources\PropertyList;
use Queryr\Resources\PropertyListElement;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @covers Queryr\Resources\PropertyList
 * @covers Queryr\Resources\PropertyListElement
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyListTest extends \PHPUnit_Framework_TestCase {

	public function testSetAndGetElements() {
		$items = [
			new PropertyListElement(
				new PropertyId( 'P1' ),
				'number',
				'https://www.wikidata.org/wiki/Property:P1',
				'http://api.queryr.com/properties/P1'
			),
			new PropertyListElement(
				new PropertyId( 'P2' ),
				'string',
				'https://www.wikidata.org/wiki/Property:P2',
				'http://api.queryr.com/properties/P2'
			)
		];

		$list = new PropertyList( $items );
		$this->assertSame( $items, $list->getElements() );


	}

	public function testElement() {
		$item = new PropertyListElement(
			new PropertyId( 'P1' ),
			'number',
			'https://www.wikidata.org/wiki/Property:P1',
			'http://api.queryr.com/properties/P1'
		);

		$this->assertEquals( new PropertyId( 'P1' ), $item->getPropertyId() );
		$this->assertEquals( 'number', $item->getPropertyType() );
		$this->assertEquals( 'https://www.wikidata.org/wiki/Property:P1', $item->getWikidataUrl() );
		$this->assertEquals( 'http://api.queryr.com/properties/P1', $item->getApiUrl() );
	}

}
