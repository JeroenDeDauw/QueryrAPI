<?php

namespace Queryr\WebApi;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class LinkHeaderBuilder {

	public function buildLinkHeader( string $rel, string $url, array $query = [] ): string {
		$queryString = $query === [] ? '' : '?' . http_build_query( $query );
		return '<' . $url . $queryString . '>; rel="' . $rel . '"';
	}

}
