<?php

namespace Queryr\WebApi\ResponseModel;

use DataValues\DataValue;
use DataValues\StringValue;
use Queryr\TermStore\LabelLookup;
use Queryr\WebApi\ResponseModel\DataValues\EntityIdentityValue;
use Queryr\WebApi\UrlBuilder;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Entity\EntityIdValue;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\Snak\PropertyValueSnak;
use Wikibase\DataModel\Statement\Statement;
use Wikibase\DataModel\Statement\StatementList;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimpleStatementsBuilder {

	private $languageCode;
	private $labelLookup;
	private $urlBuilder;

	public function __construct( string $languageCode, LabelLookup $labelLookup, UrlBuilder $urlBuilder ) {
		$this->languageCode = $languageCode;
		$this->labelLookup = $labelLookup;
		$this->urlBuilder = $urlBuilder;
	}

	/**
	 * @param StatementList $statements
	 *
	 * @return array
	 */
	public function buildFromStatements( StatementList $statements ): array {
		$simpleStatements = [];

		foreach ( $statements->getPropertyIds() as $propertyId ) {
			$statementValues = $this->getStatementMainValues(
				$statements->getByPropertyId( $propertyId )->getBestStatements()
			);

			if ( !empty( $statementValues ) ) {
				$simpleStatement = new SimpleStatement();

				$simpleStatement->values = $statementValues;
				$simpleStatement->valueType = $statementValues[0]->getType();
				$simpleStatement->propertyName = $this->getEntityName( $propertyId );
				$simpleStatement->propertyId = $propertyId;
				$simpleStatement->propertyUrl = $this->urlBuilder->getApiPropertyUrl( $propertyId );

				$simpleStatements[] = $simpleStatement;
			}
		}

		return $simpleStatements;
	}

	private function getEntityName( EntityId $id ): string {
		$label = $this->labelLookup->getLabelByIdAndLanguage( $id, $this->languageCode );

		return $label === null ? $id->getSerialization() : $label;
	}

	/**
	 * @param StatementList $statements
	 *
	 * @return DataValue[]
	 */
	private function getStatementMainValues( StatementList $statements ) {
		$statementValues = [];

		foreach ( $statements->getMainSnaks() as $snak ) {
			if ( $snak instanceof PropertyValueSnak ) {
				$statementValues[] = $this->getSnakValue( $snak );
			}
		}

		return $statementValues;
	}

	private function getSnakValue( PropertyValueSnak $snak ): DataValue {
		$value = $snak->getDataValue();

		if ( $value instanceof EntityIdValue ) {
			return new EntityIdentityValue(
				$value->getEntityId(),
				$this->getEntityName( $value->getEntityId() ),
				$this->urlBuilder->getApiEntityUrl( $value->getEntityId() )
			);
		}

		return $value;
	}

}