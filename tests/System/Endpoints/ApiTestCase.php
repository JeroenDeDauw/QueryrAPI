<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Queryr\WebApi\ApiFactory;
use Queryr\WebApi\Tests\TestEnvironment;
use Silex\Application;
use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
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
		/** @noinspection PhpUnusedLocalVariableInspection */
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

	protected function assert400( Response $response, $expectedMessage ) {
		$this->assertJson( $response->getContent(), 'response is json' );

		$this->assertJsonResponse(
			[
				'message' => $expectedMessage,
				'code' => 400,
			],
			$response
		);

		$this->assertSame( 400, $response->getStatusCode() );
	}

	private function assertJsonResponse( $expected, Response $response ) {
		$this->assertSame(
			json_encode( $expected, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ),
			$response->getContent()
		);
	}

	protected function assertLinkRelNotSet( Client $client, string $linkRel ) {
		$this->assertTrue( $client->getResponse()->isSuccessful(), 'request is successful' );

		foreach ( $client->getResponse()->headers->get( 'Link', null, false ) as $linkValue ) {
			$this->assertNotContains( 'rel="' . $linkRel . '"', $linkValue );
		}

		$this->assertTrue( true );
	}

	protected function assertLinkRel( Client $client, string $linkRel, string $expected ) {
		$this->assertTrue( $client->getResponse()->isSuccessful(), 'request is successful' );

		foreach ( $client->getResponse()->headers->get( 'Link', null, false ) as $linkValue ) {
			if ( strpos( $linkValue, 'rel="' . $linkRel . '"' ) !== false ) {
				$this->assertSame( $expected, $linkValue );
				return;
			}
		}

		$this->fail( 'No link with rel "' . $linkRel . '" found.' );
	}

}