<?php

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

$_SERVER['REQUEST_URI'] = rtrim( $_SERVER['REQUEST_URI'], '/' );

require_once __DIR__ . '/../vendor/autoload.php';

$services = Queryr\WebApi\ApiServices::newFromConfig();

/**
 * @var \Silex\Application $app
 */
$app = require __DIR__ . '/../app/bootstrap.php';

$app->run();