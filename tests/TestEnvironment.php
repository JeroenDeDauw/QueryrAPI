<?php

namespace Queryr\WebApi\Tests;

use Queryr\WebApi\ApiFactory;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class TestEnvironment {

	public static function newInstance() {
		return new self();
	}

	/**
	 * @var ApiFactory
	 */
	private $factory;

	private function __construct() {
		$this->factory = ApiFactory::newFromConnectionData( [
			'driver' => 'pdo_sqlite',
			'memory' => true,
		] );

		$this->factory->newEntityStoreInstaller()->install();
		$this->factory->newTermStoreInstaller()->install();
	}

	public function getFactory(): ApiFactory {
		return $this->factory;
	}

}
