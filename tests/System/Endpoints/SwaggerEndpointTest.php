<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Symfony\Component\HttpKernel\Client;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SwaggerEndpointTest extends ApiTestCase {

	public function testValidJsonIsReturned() {
		$client = $this->createClient();
		$client->request( 'GET', '/swagger.json' );

		$this->assertTrue( $client->getResponse()->isSuccessful(), 'request is successful' );
		$this->assertJson( $client->getResponse()->getContent(), 'response is json' );
	}

	/**
	 * @depends testValidJsonIsReturned
	 */
	public function testJsonIsSwagger() {
		$client = $this->createClient();
		$swaggerData = $this->getSwaggerData( $client );

		$this->assertArrayHasKey( 'swagger', $swaggerData );
		$this->assertArrayHasKey( 'host', $swaggerData );
		$this->assertArrayHasKey( 'basePath', $swaggerData );

		$this->assertSame( '2.0', $swaggerData['swagger'] );
	}

	private function getSwaggerData( Client $client ) {
		$client->request( 'GET', '/swagger.json' );
		return json_decode( $client->getResponse()->getContent(), true );
	}

	/**
	 * @depends testJsonIsSwagger
	 */
	public function testHostIsSet() {
		$client = $this->createClient();
		$swaggerData = $this->getSwaggerData( $client );

		$this->assertSame( $client->getRequest()->getHost(), $swaggerData['host'] );
	}

	/**
	 * @depends testJsonIsSwagger
	 */
	public function testBasePathIsSet() {
		$client = $this->createClient();
		$swaggerData = $this->getSwaggerData( $client );

		$this->assertSame( $client->getRequest()->getBasePath(), $swaggerData['basePath'] );
	}

	public function testResponseHasCorsHeader() {
		$client = $this->createClient();
		$client->request( 'GET', '/swagger.json' );

		$this->assertSame(
			'*',
			$client->getResponse()->headers->get( 'access-control-allow-origin' )
		);
	}

}