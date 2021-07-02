<?php namespace Pckg\Api\Record;

use Pckg\Api\Entity\AppKeys;
use Pckg\Database\Record;

/**
 * Class AppKey
 *
 * @package Pckg\Api\Record
 * @property \Pckg\Mailo\Record\App app
 * @property $secret string
 * @property $key string
 */
class AppKey extends Record
{

    protected $entity = AppKeys::class;

    protected $protect = ['secret'];

    public function generate()
    {
        $key = config('identifier') . '-' . uuid4();
        $secret = password_hash($key, PASSWORD_DEFAULT);

        $this->setAndSave([
            'valid' => true,
            'key' => $key,
            'secret' => $secret,
        ]);

        return $this;
    }

}
