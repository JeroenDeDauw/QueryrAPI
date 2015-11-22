<?php

namespace Queryr\WebApi;

use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PaginationHeaderSetter {

	private $headers;

	public function __construct( ResponseHeaderBag $headers ) {
		$this->headers = $headers;
	}

	public function setHeaders( string $url, PaginationInfo $paginationInfo, int $resultPageSize ) {
		$linkHeaderValues = $this->getLinkHeaderValues( $url, $paginationInfo, $resultPageSize );

		if ( $linkHeaderValues !== [] ) {
			$this->headers->set( 'Link', $linkHeaderValues );
		}
	}

	private function getLinkHeaderValues( string $url, PaginationInfo $paginationInfo, int $resultPageSize ): array {
		$headerBuilder = new LinkHeaderBuilder();
		$linkHeaderValues = [];

		if ( $resultPageSize === $paginationInfo->getPerPage() ) {
			$linkHeaderValues[] = $headerBuilder->buildLinkHeader(
				'next',
				$url,
				[
					'page' => $paginationInfo->getPage() + 1,
					'per_page' => $paginationInfo->getPerPage()
				]
			);
		}

		if ( $paginationInfo->getPage() !== 1 ) {
			$linkHeaderValues[] = $headerBuilder->buildLinkHeader(
				'first',
				$url,
				[
					'page' => 1,
					'per_page' => $paginationInfo->getPerPage()
				]
			);
		}

		return $linkHeaderValues;
	}

}