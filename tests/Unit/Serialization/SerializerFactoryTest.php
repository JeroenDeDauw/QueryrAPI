<?php

namespace Tests\Queryr\Serialization;

use Queryr\Serialization\SerializerFactory;

/**
 * @covers Queryr\Serialization\SerializerFactory
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

	public function testGetStableItemSerializer() {
		$this->assertInstanceOf(
			'Serializers\Serializer',
			$this->factory->newStableItemSerializer( [] )
		);
	}

}
