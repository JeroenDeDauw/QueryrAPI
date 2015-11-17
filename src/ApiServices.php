<?php

namespace Queryr\WebApi;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Queryr\EntityStore\EntityStoreConfig;
use Queryr\EntityStore\EntityStoreFactory;
use Queryr\EntityStore\EntityStoreInstaller;
use Queryr\TermStore\TermStoreConfig;
use Queryr\TermStore\TermStoreInstaller;
use Queryr\WebApi\UseCases\ListItems\ListItemsUseCase;
use Serializers\Serializer;
use Silex\Application;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ApiServices {

	private $pimple;

	public static function newFromConfig() {
		return new self( function() {
			return DriverManager::getConnection( DatabaseConfigFile::newInstance()->read() );
		} );
	}

	public static function newFromConnectionData( array $connectionData ) {
		return new self( function() use ( $connectionData ) {
			return DriverManager::getConnection( $connectionData );
		} );
	}

	private function __construct( callable $connectionBuilder ) {
		$pimple = new \Pimple();

		$pimple['dbal_connection'] = $pimple->share( $connectionBuilder );

		$pimple['entity_store_factory'] = $pimple->share( function() use ( $pimple ) {
			return new EntityStoreFactory(
				$this->getConnection(),
				$this->getEntityStoreConfig()
			);
		} );

		$pimple['item_store'] = $pimple->share( function() use ( $pimple ) {
			return $this->getEntityStoreFactory()->newItemStore();
		} );

		$pimple['url_builder'] = $pimple->share( function() {
			return new UrlBuilder(
				array_key_exists( 'HTTP_HOST', $_SERVER ) ? 'http://' . $_SERVER['HTTP_HOST'] : 'testurl'
			);
		} );

		$this->pimple = $pimple;
	}

	public function newListItemsUseCase(): ListItemsUseCase {
		return new ListItemsUseCase(
			$this->pimple['item_store'],
			$this->pimple['url_builder']
		);
	}

	public function newItemListSerializer(): Serializer {
		return ( new \Queryr\Serialization\SerializerFactory() )->newItemListSerializer();
	}

	public function newEntityStoreInstaller(): EntityStoreInstaller {
		return new EntityStoreInstaller(
			$this->getConnection()->getSchemaManager(),
			$this->getEntityStoreConfig()
		);
	}

	public function newTermStoreInstaller(): TermStoreInstaller {
		return new TermStoreInstaller(
			$this->getConnection()->getSchemaManager(),
			$this->getTermStoreConfig()
		);
	}

	private function getEntityStoreFactory(): EntityStoreFactory {
		return $this->pimple['entity_store_factory'];
	}

	private function getConnection(): Connection {
		return $this->pimple['dbal_connection'];
	}

	private function getEntityStoreConfig(): EntityStoreConfig {
		return new EntityStoreConfig( 'es_' );
	}

	private function getTermStoreConfig(): TermStoreConfig {
		return new TermStoreConfig( 'ts_' );
	}

	public function getUrlBuilder(): UrlBuilder {
		return $this->pimple['url_builder'];
	}

}