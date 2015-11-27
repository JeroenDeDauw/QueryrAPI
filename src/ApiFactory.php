<?php

declare(strict_types=1);

namespace Queryr\WebApi;

use DataValues\Deserializers\DataValueDeserializer;
use DataValues\Serializers\DataValueSerializer;
use Deserializers\Deserializer;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Queryr\EntityStore\EntityStoreConfig;
use Queryr\EntityStore\EntityStoreFactory;
use Queryr\EntityStore\EntityStoreInstaller;
use Queryr\EntityStore\ItemStore;
use Queryr\EntityStore\PropertyStore;
use Queryr\WebApi\Serializers\SerializerFactory as QueryrSerializerFactory;
use Queryr\TermStore\LabelLookup;
use Queryr\TermStore\TermStore;
use Queryr\TermStore\TermStoreConfig;
use Queryr\TermStore\TermStoreFactory;
use Queryr\TermStore\TermStoreInstaller;
use Queryr\TermStore\TermStoreWriter;
use Queryr\WebApi\UseCases\GetItem\GetItemUseCase;
use Queryr\WebApi\UseCases\GetProperty\GetPropertyUseCase;
use Queryr\WebApi\UseCases\ListItems\ListItemsUseCase;
use Queryr\WebApi\UseCases\ListItemTypes\ListItemTypesUseCase;
use Queryr\WebApi\UseCases\ListProperties\ListPropertiesUseCase;
use Serializers\Serializer;
use Silex\Application;
use Wikibase\DataModel\DeserializerFactory;
use Wikibase\DataModel\Entity\BasicEntityIdParser;
use Wikibase\DataModel\SerializerFactory;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ApiFactory {

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

		$pimple['entity_store_factory'] = $pimple->share( function() {
			return new EntityStoreFactory(
				$this->getConnection(),
				$this->getEntityStoreConfig()
			);
		} );

		$pimple['item_store'] = $pimple->share( function() {
			return $this->getEntityStoreFactory()->newItemStore();
		} );

		$pimple['property_store'] = $pimple->share( function() {
			return $this->getEntityStoreFactory()->newPropertyStore();
		} );

		$pimple['term_store_factory'] = $pimple->share( function() {
			return new TermStoreFactory(
				$this->getConnection(),
				$this->getTermStoreConfig()
			);
		} );

		$pimple['label_lookup'] = $pimple->share( function() {
			return $this->getTermStoreFactory()->newLabelLookup();
		} );

		$pimple['url_builder'] = $pimple->share( function() {
			return new UrlBuilder(
				array_key_exists( 'HTTP_HOST', $_SERVER ) ? 'http://' . $_SERVER['HTTP_HOST'] : 'http://test.url'
			);
		} );

		$this->pimple = $pimple;
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

	private function getTermStoreFactory(): TermStoreFactory {
		return $this->pimple['term_store_factory'];
	}

	public function getConnection(): Connection {
		return $this->pimple['dbal_connection'];
	}

	public function getTermStoreWriter(): TermStoreWriter {
		return $this->getTermStoreFactory()->newTermStoreWriter();
	}

	public function getAliasesLookup(): TermStore { // https://github.com/JeroenDeDauw/TermStore/issues/1
		return $this->getTermStoreFactory()->newTermStore();
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

	public function newListItemsUseCase(): ListItemsUseCase {
		return new ListItemsUseCase(
			$this->getItemStore(),
			$this->getUrlBuilder()
		);
	}

	public function newListPropertiesUseCase(): ListPropertiesUseCase {
		return new ListPropertiesUseCase(
			$this->getPropertyStore(),
			$this->getUrlBuilder()
		);
	}

	public function newGetItemUseCase(): GetItemUseCase {
		return new GetItemUseCase(
			$this->getItemStore(),
			new SimpleItemLabelLookup( $this->getLabelLookup() ),
			$this->getEntityDeserializer()
		);
	}

	public function newGetPropertyUseCase(): GetPropertyUseCase {
		return new GetPropertyUseCase(
			$this->getPropertyStore(),
			$this->getEntityDeserializer()
		);
	}

	public function newListItemTypesUseCase(): ListItemTypesUseCase {
		return new ListItemTypesUseCase(
			$this->getItemStore(),
			$this->getLabelLookup(),
			$this->getUrlBuilder()
		);
	}

	public function getPropertyStore(): PropertyStore {
		return $this->pimple['property_store'];
	}

	public function getItemStore(): ItemStore {
		return $this->pimple['item_store'];
	}

	public function getLabelLookup(): LabelLookup {
		return $this->pimple['label_lookup'];
	}

	public function newItemListSerializer(): Serializer {
		return ( new QueryrSerializerFactory() )->newItemListSerializer();
	}

	public function newPropertyListSerializer(): Serializer {
		return ( new QueryrSerializerFactory() )->newPropertyListSerializer();
	}

	public function getEntitySerializer(): Serializer {
		$factory = new SerializerFactory( new DataValueSerializer() );
		return $factory->newEntitySerializer();
	}

	public function getEntityDeserializer(): Deserializer {
		$factory = new DeserializerFactory(
			$this->newDataValueDeserializer(),
			$this->newEntityIdParser()
		);
		return $factory->newEntityDeserializer();
	}

	private function newDataValueDeserializer(): Deserializer {
		$dataValueClasses = [
			'boolean' => 'DataValues\BooleanValue',
			'number' => 'DataValues\NumberValue',
			'string' => 'DataValues\StringValue',
			'unknown' => 'DataValues\UnknownValue',
			'globecoordinate' => 'DataValues\Geo\Values\GlobeCoordinateValue',
			'monolingualtext' => 'DataValues\MonolingualTextValue',
			'multilingualtext' => 'DataValues\MultilingualTextValue',
			'quantity' => 'DataValues\QuantityValue',
			'time' => 'DataValues\TimeValue',
			'wikibase-entityid' => 'Wikibase\DataModel\Entity\EntityIdValue',
		];

		return new DataValueDeserializer( $dataValueClasses );
	}

	private function newEntityIdParser() {
		return new BasicEntityIdParser();
	}

	public function newSimpleItemSerializer(): Serializer {
		return ( new QueryrSerializerFactory() )->newSimpleItemSerializer();
	}

	public function newSimplePropertySerializer(): Serializer {
		return ( new QueryrSerializerFactory() )->newSimplePropertySerializer();
	}

	public function getItemTypeSerializer(): Serializer {
		return ( new QueryrSerializerFactory() )->newItemTypeSerializer();
	}

}