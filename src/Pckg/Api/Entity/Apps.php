<?php namespace Pckg\Api\Entity;

use Pckg\Api\Record\App;
use Pckg\Database\Entity;

class Apps extends Entity
{

    protected $record = App::class;

    public function appKeys()
    {
        return $this->hasMany(AppKeys::class)
                    ->foreignKey('app_id');
    }

}