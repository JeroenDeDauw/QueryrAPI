<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SwaggerEndpointTest extends ApiTestCase {

	public function testSwaggerSpecIsReturned() {
		$client = $this->createClient();

		$client->request( 'GET', '/swagger.json' );

		$this->assertTrue( $client->getResponse()->isSuccessful(), 'request is successful' );
		$this->assertJson( $client->getResponse()->getContent(), 'response is json' );

		$swaggerData = json_decode( $client->getResponse()->getContent(), true );

		$this->assertArrayHasKey( 'host', $swaggerData );
		$this->assertArrayHasKey( 'basePath', $swaggerData );
	}

}