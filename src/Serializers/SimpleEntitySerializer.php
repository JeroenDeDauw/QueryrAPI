<?php

namespace Queryr\WebApi\Serializers;

use Queryr\WebApi\ResponseModel\SimpleEntityTrait;
use Serializers\Exceptions\UnsupportedObjectException;
use Serializers\Serializer;

/**
 * @access private
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimpleEntitySerializer implements Serializer {

	/**
	 * @var SimpleEntityTrait
	 */
	private $entity;

	private $statementSerializer;

	public function __construct( Serializer $statementSerializer ) {
		$this->statementSerializer = $statementSerializer;
	}

	public function serialize( $object ) {
		if ( !in_array( SimpleEntityTrait::class, class_uses( $object ) ) ) {
			throw new UnsupportedObjectException( $object, 'Can only serialize instances of SimpleEntityTrait' );
		}

		$this->entity = $object;

		return $this->serializeEntity();
	}

	private function serializeEntity(): array {
		$serialization = [ 'id' => $this->entity->ids ];

		if ( $this->entity->label !== '' ) {
			$serialization['label'] = $this->entity->label;
		}

		if ( $this->entity->description !== '' ) {
			$serialization['description'] = $this->entity->description;
		}

		if ( !empty( $this->entity->aliases ) ) {
			$serialization['aliases'] = $this->entity->aliases;
		}

		if ( $this->entity->labelUrl !== '' ) {
			$serialization['label_url'] = $this->entity->labelUrl;
		}

		if ( $this->entity->description !== '' ) {
			$serialization['description_url'] = $this->entity->descriptionUrl;
		}

		if ( $this->entity->aliasesUrl !== '' ) {
			$serialization['aliases_url'] = $this->entity->aliasesUrl;
		}

		if ( $this->entity->wikidataUrl !== '' ) {
			$serialization['wikidata_url'] = $this->entity->wikidataUrl;
		}

		$serialization['data_url'] = $this->entity->dataUrl;

		$serialization['data'] = $this->getDataSection();

		return $serialization;
	}

	private function getDataSection(): array {
		$data = [];

		foreach ( $this->entity->statements as $simpleStatement ) {
			$data[$simpleStatement->propertyId->getSerialization()] = $this->statementSerializer->serialize( $simpleStatement );
		}

		return $data;
	}

}