<?php

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new \Silex\Application();

$app->after( function( Request $request, Response $response ) {
	if( $response instanceof JsonResponse ) {
		$response->setEncodingOptions( JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
	}

	return $response;
} );

// TODO: disable for production
$app['debug'] = true;

// TODO: enable for production
//$app->error( function ( \Exception $e, $code ) {
//	return new JsonResponse(
//		[
//			'message' => $e->getMessage(),
//			'code' => $code
//		],
//		$code
//	);
//} );

$app['url_builder'] = function() {
	return new \Queryr\WebApi\UrlBuilder(
		array_key_exists( 'HTTP_HOST', $_SERVER ) ? 'http://' . $_SERVER['HTTP_HOST'] : 'testurl'
	);
};

return $app;