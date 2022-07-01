<?php

namespace Pckg\Api\Entity;

use Pckg\Api\Record\App;
use Pckg\Auth\Entity\Users;
use Pckg\Database\Entity;

/**
 * @method withAppKeys()
 */
class Apps extends Entity
{
    protected $record = App::class;

    public function appKeys()
    {
        return $this->hasMany(AppKeys::class)->foreignKey('app_id');
    }

    public function user()
    {
        return $this->belongsTo(Users::class)->foreignKey('user_id');
    }
}
