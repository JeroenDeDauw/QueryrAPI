<?php

namespace Queryr\WebApi\Tests\Fixtures;

use Queryr\WebApi\PaginationInfo;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimplePaginationInfo implements PaginationInfo {

	private $page;
	private $perPage;

	public function __construct( int $page, int $perPage ) {
		$this->page = $page;
		$this->perPage = $perPage;
	}

	public function getPerPage(): int {
		return $this->perPage;
	}

	public function getPage(): int {
		return $this->page;
	}

}