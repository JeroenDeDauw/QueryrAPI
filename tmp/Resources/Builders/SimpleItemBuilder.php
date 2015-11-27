<?php

namespace Queryr\Resources\Builders;

use Queryr\Resources\SimpleItem;
use Wikibase\DataModel\Entity\Item;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimpleItemBuilder {

	const MAIN_LANGUAGE = 'en';

	private $languageCode;
	private $statementsBuilder;

	/**
	 * @var Item
	 */
	private $item;

	/**
	 * @var SimpleItem
	 */
	private $simpleItem;

	public function __construct( $languageCode, SimpleStatementsBuilder $statementsBuilder ) {
		$this->languageCode = $languageCode;
		$this->statementsBuilder = $statementsBuilder;
	}

	public function buildFromItem( Item $item ) {
		$this->item = $item;
		$this->simpleItem = new SimpleItem();

		$this->addIdLinks();

		$this->addLabel();
		$this->addDescription();
		$this->addAliases();

		$this->addStatements();

		return $this->simpleItem;
	}

	private function addIdLinks() {
		$this->simpleItem->ids['wikidata'] = $this->item->getId()->getSerialization();
		$this->addIdLinkForLanguage( self::MAIN_LANGUAGE );
		$this->addIdLinkForLanguage( $this->languageCode );
	}

	private function addIdLinkForLanguage( $languageCode ) {
		$links = $this->item->getSiteLinkList();

		if ( $links->hasLinkWithSiteId( $languageCode . 'wiki' ) ) {
			$this->simpleItem->ids[$languageCode . '_wikipedia'] = $links->getBySiteId( $languageCode . 'wiki' )->getPageName();
		}
	}

	private function addLabel() {
		if ( $this->item->getFingerprint()->getLabels()->hasTermForLanguage( $this->languageCode ) ) {
			$this->simpleItem->label = $this->item->getFingerprint()->getLabel( $this->languageCode )->getText();
		}
	}

	private function addDescription() {
		if ( $this->item->getFingerprint()->getDescriptions()->hasTermForLanguage( $this->languageCode ) ) {
			$this->simpleItem->description = $this->item->getFingerprint()->getDescription( $this->languageCode )->getText();
		}
	}

	private function addAliases() {
		if ( $this->item->getFingerprint()->getAliasGroups()->hasGroupForLanguage( $this->languageCode ) ) {
			$this->simpleItem->aliases = $this->item->getFingerprint()->getAliasGroup( $this->languageCode )->getAliases();
		}
	}

	private function addStatements() {
		$this->simpleItem->statements = $this->statementsBuilder->buildFromStatements( $this->item->getStatements() );
	}

}
