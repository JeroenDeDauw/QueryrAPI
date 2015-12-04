<?php

namespace Queryr\WebApi\UseCases\GetProperty;

use Queryr\WebApi\ResponseModel\SimpleEntityTrait;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimpleProperty {
	use SimpleEntityTrait;

	/**
	 * @var string
	 */
	public $type = '';

}
