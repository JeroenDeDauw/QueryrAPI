<?php

namespace Queryr\Resources\Builders;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ResourceBuildingException extends \RuntimeException {

	public function __construct( $message, \Exception $previous = null ) {
		parent::__construct( $message, 0, $previous );
	}

}