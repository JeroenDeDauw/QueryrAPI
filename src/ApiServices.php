<?php

namespace Queryr\WebApi;

use Queryr\WebApi\UseCases\ListItems\ListItemsUseCase;
use Serializers\Serializer;
use Silex\Application;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ApiServices {

	private $app;

	public function __construct( Application $app ) {
		$this->app = $app;
	}

	public function newListItemsUseCase(): ListItemsUseCase {
		return new ListItemsUseCase();
	}

	public function newItemListSerializer(): Serializer {
		return ( new \Queryr\Serialization\SerializerFactory() )->newItemListSerializer();
	}

}