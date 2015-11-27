<?php

namespace Queryr\Resources\Builders;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class BuilderFactory {

	public function newSimpleItemBuilder( $languageCode, ResourceLabelLookup $labelLookup ) {
		return new SimpleItemBuilder(
			$languageCode,
			new SimpleStatementsBuilder( $languageCode, $labelLookup )
		);
	}

	public function newSimplePropertyBuilder( $languageCode ) {
		return new SimplePropertyBuilder( $languageCode );
	}

}