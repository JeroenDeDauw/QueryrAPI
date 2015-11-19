<?php

declare(strict_types=1);

namespace Queryr\WebApi\Endpoints;

use Queryr\WebApi\ApiFactory;
use Silex\Application;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
trait EndpointConstructor {

	/**
	 * @var Application
	 */
	private $app;

	/**
	 * @var ApiFactory
	 */
	private $apiFactory;

	public function __construct( Application $app, ApiFactory $apiFactory ) {
		$this->app = $app;
		$this->apiFactory = $apiFactory;
	}

}