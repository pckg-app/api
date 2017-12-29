<?php namespace Pckg\Api\Entity;

use Pckg\Api\Record\AppKey;
use Pckg\Database\Entity;
use Pckg\Mailo\Entity\Apps;

class AppKeys extends Entity
{

    protected $record = AppKey::class;

    public function app()
    {
        return $this->belongsTo(Apps::class)
                    ->foreignKey('app_id');
    }

}