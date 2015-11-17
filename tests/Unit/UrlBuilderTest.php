<?php

namespace Queryr\WebApi\Tests\Unit;

use Queryr\WebApi\UrlBuilder;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @covers Queryr\WebApi\UrlBuilder
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class UrlBuilderTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var UrlBuilder
	 */
	private $urlBuilder;

	public function setUp() {
		$this->urlBuilder = new UrlBuilder( 'http://api.queryr.com' );
	}

	public function testGetWdEntityUrl() {
		$this->assertSame(
			'https://www.wikidata.org/entity/P31',
			$this->urlBuilder->getWdEntityUrl( new PropertyId( 'P31' ) )
		);
	}

	public function testGetWdItemPageUrl() {
		$this->assertSame(
			'https://www.wikidata.org/wiki/Q64',
			$this->urlBuilder->getWdItemPageUrl( new ItemId( 'Q64' ) )
		);
	}

	public function testGetWdPropertyPageUrl() {
		$this->assertSame(
			'https://www.wikidata.org/wiki/Property:P31',
			$this->urlBuilder->getWdPropertyPageUrl( new PropertyId( 'P31' ) )
		);
	}

	public function testGetApiItemUrl() {
		$this->assertSame(
			'http://api.queryr.com/items/Q64',
			$this->urlBuilder->getApiItemUrl( new ItemId( 'Q64' ) )
		);
	}

	public function testGetApiPropertyUrl() {
		$this->assertSame(
			'http://api.queryr.com/properties/P31',
			$this->urlBuilder->getApiPropertyUrl( new PropertyId( 'P31' ) )
		);
	}

}