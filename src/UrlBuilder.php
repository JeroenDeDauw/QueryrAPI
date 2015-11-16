<?php

namespace Queryr\WebApi;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class UrlBuilder {

	private $apiUrl;
	private $wdUrl;

	public function __construct( $apiUrl ) {
		$this->apiUrl = $apiUrl;
		$this->wdUrl = 'https://www.wikidata.org';
	}

	public function getApiPath( $path ) {
		return $this->apiUrl . '/' . $path;
	}

}
