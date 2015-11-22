<?php

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;

$_SERVER['REQUEST_URI'] = rtrim( $_SERVER['REQUEST_URI'], '/' );

require_once __DIR__ . '/../vendor/autoload.php';

$apiFactory = Queryr\WebApi\ApiFactory::newFromConfig();

/**
 * @var \Silex\Application $app
 */
$app = require __DIR__ . '/../app/bootstrap.php';

$app->register( new HttpFragmentServiceProvider() );
$app->register( new ServiceControllerServiceProvider() );
$app->register( new TwigServiceProvider() );
$app->register( new UrlGeneratorServiceProvider() );

$app->register(
	new WebProfilerServiceProvider(),
	[
		'profiler.cache_dir' => __DIR__ . '/../app/cache/profiler',
		'profiler.mount_prefix' => '/_profiler',
	]
);

$app->run();