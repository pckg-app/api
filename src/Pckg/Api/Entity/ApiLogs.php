<?php

namespace Pckg\Api\Entity;

use Pckg\Api\Record\ApiLog;
use Pckg\Database\Entity;

class ApiLogs extends Entity
{
    protected $record = ApiLog::class;
}
