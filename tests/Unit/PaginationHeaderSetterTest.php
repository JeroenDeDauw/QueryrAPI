<?php

namespace Queryr\WebApi\Tests\Unit;

use Queryr\WebApi\PaginationHeaderSetter;
use Queryr\WebApi\Tests\Fixtures\SimplePaginationInfo;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @covers Queryr\WebApi\PaginationHeaderSetter
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PaginationHeaderSetterTest extends \PHPUnit_Framework_TestCase {

	public function testWhenNotOnFirstPage_firstPageAndPreviousPageAreLinked() {
		$headers = new ResponseHeaderBag();
		$headerSetter = new PaginationHeaderSetter( $headers );

		$headerSetter->setHeaders(
			'http://localhost/test',
			new SimplePaginationInfo( 5, 10 ),
			1
		);

		$this->assertSame(
			[
				'<http://localhost/test?page=1&per_page=10>; rel="first"',
				'<http://localhost/test?page=4&per_page=10>; rel="previous"',
			],
			$headers->get( 'Link', null, false )
		);
	}

	public function testWhenOnFirstPageAndFullRestSet_nextPageIsLinked() {
		$headers = new ResponseHeaderBag();
		$headerSetter = new PaginationHeaderSetter( $headers );

		$headerSetter->setHeaders(
			'http://localhost/test',
			new SimplePaginationInfo( 1, 10 ),
			10
		);

		$this->assertSame(
			[
				'<http://localhost/test?page=2&per_page=10>; rel="next"',
			],
			$headers->get( 'Link', null, false )
		);
	}

	public function testWhenOnFirstPageAndPartialRestSet_nextPageIsNotLinked() {
		$headers = new ResponseHeaderBag();
		$headerSetter = new PaginationHeaderSetter( $headers );

		$headerSetter->setHeaders(
			'http://localhost/test',
			new SimplePaginationInfo( 1, 10 ),
			9
		);

		$this->assertSame( [], $headers->get( 'Link', null, false ) );
	}

}