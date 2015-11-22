<?php

namespace Queryr\WebApi;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
interface PaginationInfo {

	/**
	 * Returns the number of entries per page. Must be a strictly positive integer.
	 * @return int
	 */
	public function getPerPage(): int;

	/**
	 * Returns the page number. Must be a strictly positive integer (one-based counting).
	 * @return int
	 */
	public function getPage(): int;

}