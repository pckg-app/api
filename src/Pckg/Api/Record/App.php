<?php

namespace Pckg\Api\Record;

use Pckg\Api\Entity\Apps;
use Pckg\Collection;
use Pckg\Database\Record;

/**
 * @property Collection $appKeys
 */
class App extends Record
{
    protected $entity = Apps::class;

    protected $toArray = ['appKeys'];

    public function generateApiKey()
    {
        return AppKey::create([
            'app_id' => $this->id,
            'valid' => true,
            'key' => randomKey(),
            'secret' => randomKey(),
        ]);
    }
}
