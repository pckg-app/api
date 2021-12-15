<?php

namespace Pckg\Api\Record;

use Pckg\Api\Entity\ApiLogs;
use Pckg\Database\Field\JsonObject;
use Pckg\Database\Record;

class ApiLog extends Record
{
    protected $entity = ApiLogs::class;

    protected $encapsulate = [
        'data' => JsonObject::class,
    ];
}
