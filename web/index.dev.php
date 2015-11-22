<?php

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

$_SERVER['REQUEST_URI'] = rtrim( $_SERVER['REQUEST_URI'], '/' );

require_once __DIR__ . '/../vendor/autoload.php';

$apiFactory = Queryr\WebApi\ApiFactory::newFromConfig();

/**
 * @var \Silex\Application $app
 */
$app = require __DIR__ . '/../app/bootstrap.php';

$app->register( new Silex\Provider\HttpFragmentServiceProvider() );
$app->register( new Silex\Provider\ServiceControllerServiceProvider() );
$app->register( new Silex\Provider\TwigServiceProvider() );
$app->register( new Silex\Provider\UrlGeneratorServiceProvider() );

$app->register( new Silex\Provider\DoctrineServiceProvider() );

$app['db'] = $apiFactory->getConnection();
$app['dbs'] = $app->share( function ( $app ) {
	$app['dbs.options.initializer']();
	return [ 'default' => $app['db'] ];
} );

$app->register(
	new Silex\Provider\WebProfilerServiceProvider(),
	[
		'profiler.cache_dir' => __DIR__ . '/../app/cache/profiler',
		'profiler.mount_prefix' => '/_profiler',
	]
);

$app->register( new Sorien\Provider\DoctrineProfilerServiceProvider() );

$app->run();