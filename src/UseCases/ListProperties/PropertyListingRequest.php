<?php

declare(strict_types=1);

namespace Queryr\WebApi\UseCases\ListProperties;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyListingRequest {

	private $limit;

	public function setPerPage( int $limit ) {
		$this->limit = $limit;
	}

	public function getPerPage(): int {
		return $this->limit;
	}

}