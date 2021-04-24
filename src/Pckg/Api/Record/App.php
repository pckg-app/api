<?php namespace Pckg\Api\Record;

use Pckg\Api\Entity\Apps;
use Pckg\Database\Record;

class App extends Record
{

    protected $entity = Apps::class;

    protected $toArray = ['appKeys'];

}
