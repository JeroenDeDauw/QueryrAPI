<?php

namespace Tests\Queryr\Serialization;

use Queryr\WebApi\Serializers\SerializerFactory;

/**
 * @covers Queryr\WebApi\Serializers\SerializerFactory
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SerializerFactoryTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var SerializerFactory
	 */
	private $factory;

	public function setUp() {
		$this->factory = new SerializerFactory();
	}

	public function testGetSimpleItemSerializer() {
		$this->assertInstanceOf(
			'Serializers\Serializer',
			$this->factory->newSimpleItemSerializer()
		);
	}

}
