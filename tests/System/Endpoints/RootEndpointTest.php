<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class RootEndpointTest extends ApiTestCase {

	public function testRootReturnsJson() {
		$client = $this->createClient();

		$client->request( 'GET', '/' );

		$this->assertTrue( $client->getResponse()->isSuccessful(), 'request is successful' );
		$this->assertJson( $client->getResponse()->getContent(), 'response is json' );

		$this->assertArrayHasKey(
			'items_url',
			json_decode( $client->getResponse()->getContent(), true ),
			'result has items_url key'
		);
	}

	public function testRootJsonHasRequiredKeys() {
		$client = $this->createClient();

		$client->request( 'GET', '/' );
		$json = json_decode( $client->getResponse()->getContent(), true );

		$this->assertArrayHasKey( 'items_url', $json );
		$this->assertArrayHasKey( 'item_label_url', $json );
		$this->assertArrayHasKey( 'item_description_url', $json );
		$this->assertArrayHasKey( 'item_aliases_url', $json );
		$this->assertArrayHasKey( 'item_data_url', $json );
		$this->assertArrayHasKey( 'all_item_types_url', $json );
		$this->assertArrayHasKey( 'properties_url', $json );
		$this->assertArrayHasKey( 'property_label_url', $json );
		$this->assertArrayHasKey( 'property_description_url', $json );
		$this->assertArrayHasKey( 'property_aliases_url', $json );
	}

}