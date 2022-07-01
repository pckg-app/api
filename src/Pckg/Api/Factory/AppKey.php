<?php

namespace Pckg\Api\Factory;

use Defuse\Crypto\Key;

class AppKey
{
    public function create(\Pckg\Api\Record\App $app)
    {
        return \Pckg\Api\Record\AppKey::create([
            'app_id' => $app->id,
            'valid' => true,
            'key' => Key::createNewRandomKey()->saveToAsciiSafeString(),
            'secret' => Key::createNewRandomKey()->saveToAsciiSafeString(),
        ]);
    }
}
