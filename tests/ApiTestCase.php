<?php

namespace Queryr\WebApi\Tests;

use Silex\Application;
use Silex\WebTestCase;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class ApiTestCase extends WebTestCase {

	public function createApplication() : Application {
		$app = require __DIR__. ' /../web/index.php';

		$app['debug'] = true;
		unset( $app['exception_handler'] );

		return $app;
	}

}