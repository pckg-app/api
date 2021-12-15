<?php namespace Pckg\Api\Migration;

use Pckg\Migration\Migration;

class CreateApiLogsTable extends Migration
{

    public function up()
    {
        if ($this->getRepository()->getCache()->getTable('api_requests')) {
            // $this->table('api_requests')->rename('api_logs');
            $this->output('Manually execute: RENAME TABLE `api_requests` TO `api_logs`;');
            $this->output('Then clear cache and rerun the migration to add the type field');
            return;
        }

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
    }

}
