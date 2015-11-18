<?php

namespace Queryr\WebApi;

/**
 * Abuse of exceptions to indicate null return value since
 * PHP7 does not yet support nullable return types. Gaaah!
 */
class NoNullableReturnTypesException extends \RuntimeException {
}
