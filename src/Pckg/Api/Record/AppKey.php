<?php namespace Pckg\Api\Record;

use Pckg\Api\Entity\AppKeys;
use Pckg\Database\Record;

/**
 * Class AppKey
 *
 * @package Pckg\Api\Record
 * @property \Pckg\Mailo\Record\App app
 */
class AppKey extends Record
{

    protected $entity = AppKeys::class;

}