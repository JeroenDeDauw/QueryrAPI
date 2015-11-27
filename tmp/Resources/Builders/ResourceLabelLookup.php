<?php

namespace Queryr\Resources\Builders;

use Wikibase\DataModel\Entity\EntityId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
interface ResourceLabelLookup {

	/**
	 * @param EntityId $id
	 * @param string $languageCode
	 *
	 * @return string|null
	 * @throws ResourceBuildingException
	 */
	public function getLabelByIdAndLanguage( EntityId $id, $languageCode );

}
