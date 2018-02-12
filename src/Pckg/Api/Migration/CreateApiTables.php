<?php namespace Pckg\Api\Migration;

use Pckg\Migration\Migration;

class CreateApiTables extends Migration
{

    public function up()
    {
        $apps = $this->table('apps');
        $apps->integer('user_id')->references('users');
        $apps->title();

        $appKeys = $this->table('app_keys');
        $appKeys->integer('app_id')->references('apps');
        $appKeys->boolean('valid');
        $appKeys->varchar('key', 128);

        $this->save();
    }

}