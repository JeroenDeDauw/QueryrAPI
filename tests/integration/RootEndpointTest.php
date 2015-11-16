<?php

use Silex\Application;
use Silex\WebTestCase;

class ContactFormTest extends WebTestCase {

	public function createApplication() : Application {
		$app = require __DIR__. ' /../../web/index.php';

		$app['debug'] = true;
		unset( $app['exception_handler'] );

		return $app;
	}

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
		$this->assertArrayHasKey( 'item_types_url', $json );
		$this->assertArrayHasKey( 'properties_url', $json );
	}

}