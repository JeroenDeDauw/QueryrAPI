<?php

declare(strict_types=1);

namespace Queryr\WebApi\UseCases\ListProperties;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyListingRequest {

	private $limit;
	private $page;

	public function setPerPage( int $limit ) {
		$this->limit = $limit;
	}

	public function getPerPage(): int {
		return $this->limit;
	}

	public function setPage( int $page ) {
		$this->page = $page;
	}

	public function getPage(): int {
		return $this->page;
	}

}
