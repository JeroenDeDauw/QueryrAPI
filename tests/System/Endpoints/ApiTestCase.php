<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Queryr\WebApi\ApiFactory;
use Queryr\WebApi\Tests\TestEnvironment;
use Silex\Application;
use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

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

	protected function assertSuccessResponse( $expected, Response $response ) {
		$this->assertTrue( $response->isSuccessful(), 'request is successful' );
		$this->assertJson( $response->getContent(), 'response is json' );

		$this->assertJsonResponse( $expected, $response );
	}

	protected function assert404( Response $response, $expectedMessage = 'Not Found' ) {
		$this->assertJson( $response->getContent(), 'response is json' );

		$this->assertJsonResponse(
			[
				'message' => $expectedMessage,
				'code' => 404,
			],
			$response
		);

		$this->assertSame( 404, $response->getStatusCode() );
	}

	private function assertJsonResponse( $expected, Response $response ) {
		$this->assertSame(
			json_encode( $expected, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ),
			$response->getContent()
		);
	}

}