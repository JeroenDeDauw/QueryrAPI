<?php

namespace Queryr\WebApi\UseCases\GetItem;

use Queryr\WebApi\ResponseModel\SimpleStatementsBuilder;
use Queryr\WebApi\UrlBuilder;
use Wikibase\DataModel\Entity\Item;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimpleItemBuilder {

	const MAIN_LANGUAGE = 'en';

	private $languageCode;
	private $statementsBuilder;
	private $urlBuilder;

	/**
	 * @var Item
	 */
	private $item;

	/**
	 * @var SimpleItem
	 */
	private $simpleItem;

	public function __construct( string $languageCode, SimpleStatementsBuilder $statementsBuilder, UrlBuilder $urlBuilder ) {
		$this->languageCode = $languageCode;
		$this->statementsBuilder = $statementsBuilder;
		$this->urlBuilder = $urlBuilder;
	}

	public function buildFromItem( Item $item ): SimpleItem {
		$this->item = $item;
		$this->simpleItem = new SimpleItem();

		$this->addIdLinks();

		$this->addLabel();
		$this->addDescription();
		$this->addAliases();

		$this->addHyperlinks();

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

		if ( $links->hasLinkWithSiteId( $this->getWikiId( $languageCode ) ) ) {
			$this->simpleItem->ids[$languageCode . '_wikipedia'] =
				$links->getBySiteId( $this->getWikiId( $languageCode ) )->getPageName();
		}
	}

	private function getWikiId( string $languageCode ): string {
		return $languageCode . 'wiki';
	}

	private function addLabel() {
		if ( $this->item->getFingerprint()->hasLabel( $this->languageCode ) ) {
			$this->simpleItem->label = $this->item->getFingerprint()->getLabel( $this->languageCode )->getText();
		}
	}

	private function addDescription() {
		if ( $this->item->getFingerprint()->hasDescription( $this->languageCode ) ) {
			$this->simpleItem->description = $this->item->getFingerprint()->getDescription( $this->languageCode )->getText();
		}
	}

	private function addAliases() {
		if ( $this->item->getFingerprint()->hasAliasGroup( $this->languageCode ) ) {
			$this->simpleItem->aliases = $this->item->getFingerprint()->getAliasGroup( $this->languageCode )->getAliases();
		}
	}

	private function addHyperlinks() {
		$builder = $this->urlBuilder;
		$id = $this->item->getId();

		$this->simpleItem->labelUrl = $builder->getApiItemLabelUrl( $id );
		$this->simpleItem->descriptionUrl = $builder->getApiItemDescriptionUrl( $id );
		$this->simpleItem->aliasesUrl = $builder->getApiItemAliasesUrl( $id );

		$this->simpleItem->wikidataUrl = $builder->getWdEntityUrl( $id );

		$this->addWikipediaHtmlUrl();

		$this->simpleItem->dataUrl = $builder->getApiItemDataUrl( $id );
	}

	private function addStatements() {
		$this->simpleItem->statements = $this->statementsBuilder->buildFromStatements( $this->item->getStatements() );
	}

	private function addWikipediaHtmlUrl() {
		$wikiId = $this->getWikiId( $this->languageCode );

		if ( $this->item->getSiteLinkList()->hasLinkWithSiteId( $wikiId ) ) {
			$this->simpleItem->wikipediaHtmlUrl = $this->urlBuilder->getSiteLinkBasedRerirectUrl(
				$wikiId,
				$this->item->getId()
			);
		}
	}

}
