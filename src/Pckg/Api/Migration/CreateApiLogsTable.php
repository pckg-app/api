<?php

namespace Pckg\Api\Migration;

use Pckg\Migration\Migration;

class CreateApiLogsTable extends Migration
{
    public function up()
    {
        /**
         * Api requests
         */
        $apiLogs = $this->table('api_logs');
        $apiLogs->varchar('type')->nullable();
        $apiLogs->datetime('created_at');
        $apiLogs->json('data');
        $apiLogs->varchar('ip');
        $apiLogs->varchar('url');

        $this->save();

        return $this;
    }
}
