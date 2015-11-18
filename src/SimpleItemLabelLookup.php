<?php

namespace Queryr\WebApi;

use Queryr\Resources\Builders\ResourceBuildingException;
use Queryr\Resources\Builders\ResourceLabelLookup;
use Queryr\TermStore\LabelLookup;
use Queryr\TermStore\TermStoreException;
use Wikibase\DataModel\Entity\EntityId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimpleItemLabelLookup implements ResourceLabelLookup {

	private $termStore;

	public function __construct( LabelLookup $termStore ) {
		$this->termStore = $termStore;
	}

	/**
	 * @param EntityId $id
	 * @param string $languageCode
	 *
	 * @return string|null
	 * @throws ResourceBuildingException
	 */
	public function getLabelByIdAndLanguage( EntityId $id, $languageCode ) {
		try {
			return $this->termStore->getLabelByIdAndLanguage( $id, $languageCode );
		}
		catch ( TermStoreException $ex ) {
			throw new ResourceBuildingException( $ex->getMessage(), $ex );
		}
	}
}
