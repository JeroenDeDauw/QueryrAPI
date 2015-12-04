<?php

namespace Queryr\WebApi\UseCases\GetItem;

use Queryr\WebApi\ResponseModel\SimpleEntityTrait;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimpleItem {
	use SimpleEntityTrait;

	/**
	 * @var string
	 */
	public $wikipediaHtmlUrl = '';

}
