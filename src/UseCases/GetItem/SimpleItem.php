<?php

namespace Queryr\WebApi\UseCases\GetItem;

use Queryr\WebApi\ResponseModel\SimpleStatement;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SimpleItem {

	/**
	 * @var string[]
	 */
	public $ids = [];

	/**
	 * @var string
	 */
	public $label = '';

	/**
	 * @var string
	 */
	public $description = '';

	/**
	 * @var string[]
	 */
	public $aliases = [];

	/**
	 * @var SimpleStatement[]
	 */
	public $statements = [];

}
