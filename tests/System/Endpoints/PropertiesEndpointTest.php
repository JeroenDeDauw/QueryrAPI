<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

/**
 * @covers Queryr\WebApi\UseCases\ListProperties\ListPropertiesUseCase
 * @covers Queryr\WebApi\UseCases\ListProperties\PropertyListingRequest
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertiesEndpointTest extends ApiTestCase {

	public function testPropertiesEndpointReturnsEmptyJsonArray() {
		$client = $this->createClient();

		$client->request( 'GET', '/properties' );

		$this->assertTrue( $client->getResponse()->isSuccessful(), 'request is successful' );
		$this->assertJson( $client->getResponse()->getContent(), 'response is json' );

		$this->assertSame( '[]', $client->getResponse()->getContent() );
	}

}