<?php

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

declare(strict_types=1);

use Queryr\WebApi\Endpoints\GetItemDataEndpoint;
use Queryr\WebApi\Endpoints\GetItemEndpoint;
use Queryr\WebApi\Endpoints\GetItemPropertyDataEndpoint;
use Queryr\WebApi\Endpoints\GetItemsEndpoint;
use Queryr\WebApi\Endpoints\GetItemTypesEndpoint;
use Queryr\WebApi\Endpoints\GetPropertiesEndpoint;
use Queryr\WebApi\Endpoints\GetPropertyEndpoint;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * These variables need to be in scope when this file is included:
 *
 * @var \Silex\Application $app
 * @var \Queryr\WebApi\ApiFactory $apiFactory
 */

$app->get(
	'/',
	function( Request $request ) use ( $app ) {
		$api = [
			'items_url' => $request->getUriForPath( '/items{/item_id}' ),
			'item_label_url' => $request->getUriForPath( '/items/{item_id}/label' ),
			'item_description_url' => $request->getUriForPath( '/items/{item_id}/description' ),
			'item_aliases_url' => $request->getUriForPath( '/items/{item_id}/aliases' ),
			'item_data_url' => $request->getUriForPath( '/items/{item_id}/data{/property_label}' ),
			'all_item_types_url' => $request->getUriForPath( '/items/types' ),
			'properties_url' => $request->getUriForPath( '/properties{/property_id}' ),
			'property_label_url' => $request->getUriForPath( '/properties{/property_id}/label' ),
			'property_description_url' => $request->getUriForPath( '/properties{/property_id}/description' ),
			'property_aliases_url' => $request->getUriForPath( '/properties{/property_id}/aliases' ),
		];

		return $app->json( $api );
	}
);

$app->get(
	'items',
	function( Request $request ) use ( $app, $apiFactory ) {
		return ( new GetItemsEndpoint( $app, $apiFactory ) )->getResult( $request );
	}
);

$ITEM_ID_REGEX = '(Q|q)[1-9]\d*';
$PROPERTY_ID_REGEX = '(P|p)[1-9]\d*';

$app->get(
	'items/{id}',
	function( Application $app, string $id ) use ( $apiFactory ) {
		return ( new GetItemEndpoint( $app, $apiFactory ) )->getResult( $id );
	}
)->assert( 'id', $ITEM_ID_REGEX );

$app->get(
	'items/{id}/label',
	function( Application $app, string $id ) use ( $apiFactory ) {
		$label = $apiFactory->getLabelLookup()->getLabelByIdAndLanguage(
			new \Wikibase\DataModel\Entity\ItemId( $id ),
			'en'
		);

		if ( $label === null ) {
			return $this->app->json( [
				'message' => 'Not Found',
				'code' => 404,
			], 404 );
		}

		return $app->json( $label );
	}
)->assert( 'id', $ITEM_ID_REGEX );

$app->get(
	'items/{id}/description',
	function( Application $app, string $id ) use ( $apiFactory ) {
		$itemRow = $apiFactory->getItemStore()->getItemRowByNumericItemId( (int)substr( $id, 1 ) );

		if ( $itemRow === null ) {
			return $this->app->json( [
				'message' => 'Not Found',
				'code' => 404,
			], 404 );
		}

		/**
		 * @var Wikibase\DataModel\Entity\Item $item
		 */
		$item = $apiFactory->getEntityDeserializer()->deserialize(
			json_decode( $itemRow->getItemJson(), true )
		);

		if ( !$item->getFingerprint()->hasDescription( 'en' ) ) {
			return $this->app->json( [
				'message' => 'Not Found',
				'code' => 404,
			], 404 );
		}

		return $app->json( $item->getFingerprint()->getDescription( 'en' )->getText() );
	}
)->assert( 'id', $ITEM_ID_REGEX );

$app->get(
	'items/{id}/aliases',
	function( Application $app, string $id ) use ( $apiFactory ) {
		$aliases = $apiFactory->getAliasesLookup()->getAliasesByIdAndLanguage(
			new \Wikibase\DataModel\Entity\ItemId( $id ),
			'en'
		);

		return $app->json( $aliases );
	}
)->assert( 'id', $ITEM_ID_REGEX );

$app->get(
	'items/{id}/data',
	function( Application $app, string $id ) use ( $apiFactory ) {
		return ( new GetItemDataEndpoint( $app, $apiFactory ) )->getResult( $id );
	}
)->assert( 'id', $ITEM_ID_REGEX );

$app->get(
	'items/{item_id}/data/{property_label}',
	function( Application $app, string $item_id, string $property_label ) use ( $apiFactory ) {
		return ( new GetItemPropertyDataEndpoint( $app, $apiFactory ) )->getResult( $item_id, $property_label );
	}
)->assert( 'item_id', $ITEM_ID_REGEX );

$app->get(
	'items/types',
	function( Request $request ) use ( $app, $apiFactory ) {
		return ( new GetItemTypesEndpoint( $app, $apiFactory ) )->getResult( $request );
	}
);

$app->get(
	'properties',
	function( Request $request ) use ( $app, $apiFactory ) {
		return ( new GetPropertiesEndpoint( $app, $apiFactory ) )->getResult( $request );
	}
);

$app->get(
	'properties/{id}',
	function( Application $app, string $id ) use ( $apiFactory ) {
		return ( new GetPropertyEndpoint( $app, $apiFactory ) )->getResult( $id );
	}
)->assert( 'id', $PROPERTY_ID_REGEX );

$app->get(
	'properties/{id}/label',
	function( Application $app, string $id ) use ( $apiFactory ) {
		$label = $apiFactory->getLabelLookup()->getLabelByIdAndLanguage(
			new \Wikibase\DataModel\Entity\PropertyId( $id ),
			'en'
		);

		if ( $label === null ) {
			return $this->app->json( [
				'message' => 'Not Found',
				'code' => 404,
			], 404 );
		}

		return $app->json( $label );
	}
)->assert( 'id', $PROPERTY_ID_REGEX );

$app->get(
	'properties/{id}/description',
	function( Application $app, string $id ) use ( $apiFactory ) {
		$propertyRow = $apiFactory->getPropertyStore()->getPropertyRowByNumericPropertyId( (int)substr( $id, 1 ) );

		if ( $propertyRow === null ) {
			return $this->app->json( [
				'message' => 'Not Found',
				'code' => 404,
			], 404 );
		}

		/**
		 * @var Wikibase\DataModel\Entity\Property $property
		 */
		$property = $apiFactory->getEntityDeserializer()->deserialize(
			json_decode( $propertyRow->getPropertyJson(), true )
		);

		if ( !$property->getFingerprint()->hasDescription( 'en' ) ) {
			return $this->app->json( [
				'message' => 'Not Found',
				'code' => 404,
			], 404 );
		}

		return $app->json( $property->getFingerprint()->getDescription( 'en' )->getText() );
	}
)->assert( 'id', $PROPERTY_ID_REGEX );

$app->get(
	'properties/{id}/aliases',
	function( Application $app, string $id ) use ( $apiFactory ) {
		$aliases = $apiFactory->getAliasesLookup()->getAliasesByIdAndLanguage(
			new \Wikibase\DataModel\Entity\PropertyId( $id ),
			'en'
		);

		return $app->json( $aliases );
	}
)->assert( 'id', $PROPERTY_ID_REGEX );

$swaggerRoute = function( Request $request ) {
	return str_replace(
		[
			'you_should_probably_replace_this_by_the_actual_host',
			'you_should_probably_replace_this_by_the_actual_basePath',
		],
		[
			$request->getHost(),
			$request->getBasePath()
		],
		file_get_contents( __DIR__ . '/swagger.json' )
	);
};

$app->get( 'swagger', $swaggerRoute );
$app->get( 'swagger.json', $swaggerRoute );

return $app;