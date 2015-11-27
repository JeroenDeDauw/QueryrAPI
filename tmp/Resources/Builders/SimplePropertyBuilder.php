<?php

namespace Queryr\Resources\Builders;

use Queryr\Resources\SimpleProperty;
use Wikibase\DataModel\Entity\Property;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimplePropertyBuilder {

	const MAIN_LANGUAGE = 'en';

	private $languageCode;

	/**
	 * @var Property
	 */
	private $property;

	/**
	 * @var SimpleProperty
	 */
	private $simpleProperty;

	public function __construct( $languageCode ) {
		$this->languageCode = $languageCode;
	}

	public function buildFromProperty( Property $property ) {
		$this->property = $property;
		$this->simpleProperty = new SimpleProperty();

		$this->addIdLinks();

		$this->addLabel();
		$this->addDescription();
		$this->addAliases();

		$this->addType();

		return $this->simpleProperty;
	}

	private function addIdLinks() {
		$this->simpleProperty->ids['wikidata'] = $this->property->getId()->getSerialization();
	}

	private function addLabel() {
		if ( $this->property->getFingerprint()->getLabels()->hasTermForLanguage( $this->languageCode ) ) {
			$this->simpleProperty->label = $this->property->getFingerprint()->getLabel( $this->languageCode )->getText();
		}
	}

	private function addDescription() {
		if ( $this->property->getFingerprint()->getDescriptions()->hasTermForLanguage( $this->languageCode ) ) {
			$this->simpleProperty->description = $this->property->getFingerprint()->getDescription( $this->languageCode )->getText();
		}
	}

	private function addAliases() {
		if ( $this->property->getFingerprint()->getAliasGroups()->hasGroupForLanguage( $this->languageCode ) ) {
			$this->simpleProperty->aliases = $this->property->getFingerprint()->getAliasGroup( $this->languageCode )->getAliases();
		}
	}

	private function addType() {
		$this->simpleProperty->type = $this->property->getDataTypeId();
	}

}
