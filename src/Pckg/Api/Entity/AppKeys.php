<?php namespace Pckg\Api\Entity;

use Pckg\Api\Record\AppKey;
use Pckg\Database\Entity;

class AppKeys extends Entity
{

    protected $record = AppKey::class;

    protected $appsEntity = Apps::class;

    public function app()
    {
        return $this->belongsTo($this->appsEntity)
                    ->foreignKey('app_id');
    }

}