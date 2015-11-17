<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Queryr\WebApi\Tests\TestEnvironment;
use Silex\Application;
use Silex\WebTestCase;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class ApiTestCase extends WebTestCase {

	public function createApplication() : Application {
		$services = TestEnvironment::newInstance()->getServices();
		$app = require __DIR__ . ' /../../../app/bootstrap.php';

		$app['debug'] = true;
		unset( $app['exception_handler'] );

		return $app;
	}

}