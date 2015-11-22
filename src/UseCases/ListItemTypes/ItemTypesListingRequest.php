<?php

declare(strict_types=1);

namespace Queryr\WebApi\UseCases\ListItemTypes;

use Queryr\WebApi\PaginationInfo;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemTypesListingRequest implements PaginationInfo {

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

	public function getLanguageCode(): string {
		return 'en';
	}

}