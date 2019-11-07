<?php namespace Pckg\Api\Resolver;

use Pckg\Api\Record\AppKey;
use Pckg\Api\Record\AppKey as AppKeyRecord;
use Pckg\Framework\Provider\RouteResolver;

class ApiKey implements RouteResolver
{

    /**
     * @param $value
     *
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
     * @param $record
     *
     * @return mixed
     */
    public function parametrize($record)
    {
        return $record->key;
    }

}