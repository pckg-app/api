<?php namespace Pckg\Api\Resolver;

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
        $apiKey = request()->header($this->header ?? 'X-Api-Key');

        return AppKeyRecord::getOrFail(['key' => $apiKey, 'valid' => true]);
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