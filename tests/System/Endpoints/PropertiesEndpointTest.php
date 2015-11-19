<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

use Symfony\Component\HttpKernel\Client;
use Wikibase\DataFixtures\Properties\CountryProperty;
use Wikibase\DataFixtures\Properties\InstanceOfProperty;
use Wikibase\DataFixtures\Properties\PostalCodeProperty;

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

	private function storeThreeProperties() {
		$this->testEnvironment->insertProperty( ( new CountryProperty() )->newProperty() );
		$this->testEnvironment->insertProperty( ( new InstanceOfProperty() )->newProperty() );
		$this->testEnvironment->insertProperty( ( new PostalCodeProperty() )->newProperty() );
	}

	public function testGivenPageOffsetBeyondLastProperty_noPropertiesAreShown() {
		$this->storeThreeProperties();
		$client = $this->createClient();

		$client->request( 'GET', '/properties?page=2' );

		$this->assertSuccessResponse( [], $client->getResponse() );
	}

	public function testGivenPageOffsetBeyondLastProperty_noNextLinkHeaderIsSet() {
		$this->storeThreeProperties();
		$client = $this->createClient();

		$client->request( 'GET', '/properties?page=2' );

		$this->assertLinkRelNotSet( $client, 'next' );
	}

	public function testGivenPageWithLessThanMaxProperties_noNextLinkHeaderIsSet() {
		$this->storeThreeProperties();
		$client = $this->createClient();

		$client->request( 'GET', '/properties?per_page=5' );

		$this->assertLinkRelNotSet( $client, 'next' );
	}

	public function testWhenOnFirstPage_noFirstLinkHeaderIsSet() {
		$this->storeThreeProperties();
		$client = $this->createClient();

		$client->request( 'GET', '/properties' );

		$this->assertLinkRelNotSet( $client, 'first' );
	}

	public function testWhenFurtherResults_nextLinkHeaderIsSet() {
		$this->storeThreeProperties();
		$client = $this->createClient();

		$client->request( 'GET', '/properties?per_page=2' );

		$this->assertLinkRel(
			$client,
			'next',
			'<http://localhost/properties?page=2&per_page=2>; rel="next"'
		);
	}

	public function testNotOnFirstPage_firstLinkHeaderIsSet() {
		$this->storeThreeProperties();
		$client = $this->createClient();

		$client->request( 'GET', '/properties?page=42&per_page=23' );

		$this->assertLinkRel(
			$client,
			'first',
			'<http://localhost/properties?page=1&per_page=23>; rel="first"'
		);
	}

	private function assertLinkRelNotSet( Client $client, string $linkRel ) {
		foreach ( (array)$client->getResponse()->headers->get( 'Link' ) as $linkValue ) {
			$this->assertNotContains( 'rel="' . $linkRel . '"', $linkValue );
		}
		$this->assertTrue( true );
	}

	private function assertLinkRel( Client $client, string $linkRel, string $expected ) {
		foreach ( (array)$client->getResponse()->headers->get( 'Link' ) as $linkValue ) {
			if ( strpos( $linkValue, 'rel="' . $linkRel . '"' ) !== false ) {
				$this->assertSame( $expected, $linkValue );
			}
		}
		$this->assertTrue( true );
	}

}