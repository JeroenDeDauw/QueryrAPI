<?php

namespace Queryr\WebApi\Serializers;

use Queryr\WebApi\UrlBuilder;
use Queryr\WebApi\UseCases\GetItem\SimpleItem;
use Serializers\Exceptions\UnsupportedObjectException;
use Serializers\Serializer;

/**
 * @access private
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimpleItemSerializer implements Serializer {

	private $entitySerializer;
	private $urlBuilder;

	public function __construct( Serializer $simpleEntitySerializer, UrlBuilder $urlBuilder ) {
		$this->entitySerializer = $simpleEntitySerializer;
		$this->urlBuilder = $urlBuilder;
	}

	public function serialize( $object ) {
		if ( !( $object instanceof SimpleItem ) ) {
			throw new UnsupportedObjectException( $object, 'Can only serialize instances of SimpleItem' );
		}

		return $this->serializeItem( $object );
	}

	private function serializeItem( SimpleItem $item ): array {
		$serialization = $this->entitySerializer->serialize( $item );

		if ( $item->wikipediaHtmlUrl !== '' ) {
			$serialization['wikipedia_html_url'] = $item->wikipediaHtmlUrl;
		}

		return $serialization;
	}

}