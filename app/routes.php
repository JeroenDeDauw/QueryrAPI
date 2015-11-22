<?php

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

declare(strict_types=1);

use Queryr\WebApi\Endpoints\GetItemDataEndpoint;
use Queryr\WebApi\Endpoints\GetItemEndpoint;
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
			'item_aliases_url' => $request->getUriForPath( '/items/{item_id}/aliases' ),
			'item_data_url' => $request->getUriForPath( '/items/{item_id}/data' ),
			'all_item_types_url' => $request->getUriForPath( '/items/types' ),
			'properties_url' => $request->getUriForPath( '/properties{/property_id}' ),
			'property_label_url' => $request->getUriForPath( '/properties{/property_id}/label' ),
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
	'properties/{id}/aliases',
	function( Application $app, string $id ) use ( $apiFactory ) {
		$aliases = $apiFactory->getAliasesLookup()->getAliasesByIdAndLanguage(
			new \Wikibase\DataModel\Entity\PropertyId( $id ),
			'en'
		);

		return $app->json( $aliases );
	}
)->assert( 'id', $PROPERTY_ID_REGEX );

return $app;