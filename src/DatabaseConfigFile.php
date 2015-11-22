<?php

namespace Queryr\WebApi;

use RuntimeException;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class DatabaseConfigFile {

	private $configPath;

	public static function newInstance() {
		return new self();
	}

	private function __construct() {
		$this->configPath = __DIR__ . '/../app/config/db.json';
	}

	/**
	 * @throws RuntimeException
	 */
	public function read(): array {
		$configJson = @file_get_contents( $this->configPath );

		if ( !is_string( $configJson ) ) {
			throw new RuntimeException( 'Could not read the config file' );
		}

		return json_decode( $configJson, true );
	}

}

