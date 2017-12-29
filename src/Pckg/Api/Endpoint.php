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

    /**
     * @param array $data
     *
     * @return Endpoint|$this
     */
    public function create($data = [])
    {
        return $this->postAndDataResponse($data, $this->path, $this->path);
    }

    protected function postAndDataResponse($data = [], $path = null, $key = null, $options = [])
    {
        if (!$path) {
            $path = $this->path;
        }

        if (!$key) {
            $key = $path;
        }

        $this->api->postApi($path, $data, $options);

        $this->data = new Object($this->api->getApiResponse($key));

        return $this;
    }

    protected function getAndDataResponse($path = null, $key = null, $options = [])
    {
        if (!$path) {
            $path = $this->path;
        }

        if (!$key) {
            $key = $path;
        }

        $this->api->getApi($path, $options);

        if ($key) {
            $this->data = new Object($this->api->getApiResponse($key));
        }

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