<?php

namespace Pckg\Api\Factory;

class App
{
    public function create(array $data)
    {
        return \Pckg\Api\Record\App::create($data);
    }
}
