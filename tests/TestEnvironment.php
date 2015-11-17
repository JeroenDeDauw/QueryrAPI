<?php

namespace Queryr\WebApi\Tests;

use Queryr\WebApi\ApiServices;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class TestEnvironment {

	public static function newInstance() {
		return new self();
	}

	/**
	 * @var ApiServices
	 */
	private $factory;

	private function __construct() {
		$this->factory = ApiServices::newFromConnectionData( [
			'driver' => 'pdo_sqlite',
			'memory' => true,
		] );

		$this->factory->newEntityStoreInstaller()->install();
		$this->factory->newTermStoreInstaller()->install();
	}

	public function getServices(): ApiServices {
		return $this->factory;
	}

}
