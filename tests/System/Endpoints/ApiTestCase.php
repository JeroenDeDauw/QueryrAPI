<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Queryr\WebApi\ApiFactory;
use Queryr\WebApi\Tests\TestEnvironment;
use Silex\Application;
use Silex\WebTestCase;
use Symfony\Component\HttpKernel\Client;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class ApiTestCase extends WebTestCase {

	/**
	 * @var TestEnvironment
	 */
	protected $testEnvironment;

	/**
	 * @var ApiFactory
	 */
	protected $apiFactory;

	public function setUp() {
		$this->testEnvironment = TestEnvironment::newInstance();
		$this->apiFactory = $this->testEnvironment->getFactory();
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