<?php

namespace Queryr\WebApi\UseCases\GetProperty;

use Queryr\WebApi\ResponseModel\SimpleStatementsBuilder;
use Queryr\WebApi\UrlBuilder;
use Wikibase\DataModel\Entity\Property;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimplePropertyBuilder {

	const MAIN_LANGUAGE = 'en';

	private $languageCode;
	private $statementsBuilder;
	private $urlBuilder;

	/**
	 * @var Property
	 */
	private $property;

	/**
	 * @var SimpleProperty
	 */
	private $simpleProperty;

	public function __construct( string $languageCode, SimpleStatementsBuilder $statementsBuilder, UrlBuilder $urlBuilder ) {
		$this->languageCode = $languageCode;
		$this->statementsBuilder = $statementsBuilder;
		$this->urlBuilder = $urlBuilder;
	}

	public function buildFromProperty( Property $property ): SimpleProperty {
		$this->property = $property;
		$this->simpleProperty = new SimpleProperty();

		$this->addIdLinks();
		$this->addType();

		$this->addLabel();
		$this->addDescription();
		$this->addAliases();

		$this->addHyperlinks();

		$this->addStatements();

		return $this->simpleProperty;
	}

	private function addIdLinks() {
		$this->simpleProperty->ids['wikidata'] = $this->property->getId()->getSerialization();
	}

	private function addLabel() {
		if ( $this->property->getFingerprint()->hasLabel( $this->languageCode ) ) {
			$this->simpleProperty->label = $this->property->getFingerprint()->getLabel( $this->languageCode )->getText();
		}
	}

	private function addDescription() {
		if ( $this->property->getFingerprint()->hasDescription( $this->languageCode ) ) {
			$this->simpleProperty->description = $this->property->getFingerprint()->getDescription( $this->languageCode )->getText();
		}
	}

	private function addAliases() {
		if ( $this->property->getFingerprint()->hasAliasGroup( $this->languageCode ) ) {
			$this->simpleProperty->aliases = $this->property->getFingerprint()->getAliasGroup( $this->languageCode )->getAliases();
		}
	}

	private function addHyperlinks() {
		$builder = $this->urlBuilder;
		$id = $this->property->getId();

		$this->simpleProperty->labelUrl = $builder->getApiPropertyLabelUrl( $id );
		$this->simpleProperty->descriptionUrl = $builder->getApiPropertyDescriptionUrl( $id );
		$this->simpleProperty->aliasesUrl = $builder->getApiPropertyAliasesUrl( $id );

		$this->simpleProperty->wikidataUrl = $builder->getWdEntityUrl( $id );

		$this->simpleProperty->dataUrl = $builder->getApiPropertyDataUrl( $id );
	}

	private function addType() {
		$this->simpleProperty->type = $this->property->getDataTypeId();
	}

	private function addStatements() {
		$this->simpleProperty->statements = $this->statementsBuilder->buildFromStatements( $this->property->getStatements() );
	}

}
