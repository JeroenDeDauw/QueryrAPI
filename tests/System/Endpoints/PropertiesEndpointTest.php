<?php

namespace Queryr\WebApi\Tests\System\Endpoints;

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

		$this->assertFalse( $client->getResponse()->headers->has( 'Link' ) );
	}

	public function testWhenFurtherResults_nextLinkHeaderIsSet() {
		$this->storeThreeProperties();
		$client = $this->createClient();

		$client->request( 'GET', '/properties?per_page=2' );

		$this->assertSame(
			'<http://localhost/properties?page=2&per_page=2>; rel="next"',
			$client->getResponse()->headers->get( 'Link' )
		);
	}

}