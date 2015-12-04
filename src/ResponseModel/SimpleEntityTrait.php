<?php

namespace Queryr\WebApi\ResponseModel;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
trait SimpleEntityTrait {

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

	/**
	 * @var string
	 */
	public $dataUrl = '';

	/**
	 * @var string
	 */
	public $labelUrl = '';

	/**
	 * @var string
	 */
	public $descriptionUrl = '';

	/**
	 * @var string
	 */
	public $aliasesUrl = '';

	/**
	 * @var string
	 */
	public $wikidataUrl = '';

}
