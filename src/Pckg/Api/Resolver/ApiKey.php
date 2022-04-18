<?php

namespace Pckg\Api\Resolver;

use Pckg\Api\Record\AppKey;
use Pckg\Framework\Provider\RouteResolver;

/**
 * @property ?string $header
 */
class ApiKey implements RouteResolver
{
    /**
     * @return mixed|\Pckg\Database\Record
     */
    public function resolve($value)
    {
        return AppKey::getOrFail(['key' => $value ?? $this->fetchValue(), 'valid' => true]);
    }

    public function fetchValue()
    {
        return request()->header($this->header ?? 'X-Api-Key');
    }

    /**
     * @return mixed
     */
    public function parametrize($record)
    {
        return $record->key;
    }
}
