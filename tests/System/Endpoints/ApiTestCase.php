<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Queryr\WebApi\ApiFactory;
use Queryr\WebApi\Tests\TestEnvironment;
use Silex\Application;
use Silex\WebTestCase;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class ApiTestCase extends WebTestCase {

	/**
	 * @var ApiFactory
	 */
	protected $apiFactory;

	public function setUp() {
		$this->apiFactory = TestEnvironment::newInstance()->getFactory();
		parent::setUp();
	}

	public function createApplication() : Application {
		$apiFactory = $this->apiFactory;
		$app = require __DIR__ . ' /../../../app/bootstrap.php';

		$app['debug'] = true;
		unset( $app['exception_handler'] );

		return $app;
	}

}