<?php namespace Pckg\Api;

use ArrayAccess;
use Pckg\Database\Object;

class Endpoint implements ArrayAccess
{

    /**
     * @var Api
     */
    protected $api;

    /**
     * @var Object
     */
    protected $data;

    protected $path;

    public function __construct(Api $api = null, $data = [])
    {
        $this->api = $api;
        $this->data = is_object($data) ? $data : new Object($data);
    }

    public function data()
    {
        return $this->data->data();
    }

    public function create($data = [])
    {
        $this->api->postApi($this->path, $data);

        $this->data = new Object($this->api->getApiResponse($this->path));

        return $this;
    }

    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;

        return $this;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data->data());
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);

        return $this;
    }

    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    public function __get($key)
    {
        return $this->data[$key] ?? null;
    }

}