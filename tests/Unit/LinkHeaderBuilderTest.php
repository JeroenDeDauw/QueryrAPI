<?php

namespace Queryr\WebApi\Tests\Unit;

use Queryr\WebApi\LinkHeaderBuilder;

/**
 * @covers Queryr\WebApi\LinkHeaderBuilder
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class LinkHeaderBuilderTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var LinkHeaderBuilder
	 */
	private $headerBuilder;

	public function setUp() {
		$this->headerBuilder = new LinkHeaderBuilder();
	}

	public function testWithNoQueryString() {
		$this->assertSame(
			'<http://localhost/properties>; rel="next"',
			$this->headerBuilder->buildLinkHeader(
				'next',
				'http://localhost/properties'
			)
		);
	}

	public function testWithQueryString() {
		$this->assertSame(
			'<http://localhost/properties?page=2&per_page=5>; rel="next"',
			$this->headerBuilder->buildLinkHeader(
				'next',
				'http://localhost/properties',
				[
					'page' => 2,
					'per_page' => 5
				]
			)
		);
	}

	public function testWithQueryStringThatShouldBeEscaped() {
		$this->assertSame(
			'<http://localhost/properties?page=%3F%25%21&per_page=%26%5C>; rel="next"',
			$this->headerBuilder->buildLinkHeader(
				'next',
				'http://localhost/properties',
				[
					'page' => '?%!',
					'per_page' => '&\\'
				]
			)
		);
	}

}