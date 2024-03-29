<?php

namespace Pckg\Api;

use ArrayAccess;
use Pckg\Database\Obj;
use Pckg\Database\Query;
use Pckg\Database\Query\Select;

class Endpoint implements ArrayAccess, \JsonSerializable
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

    /**
     * @var Select|Query
     */
    protected $query;

    public function __construct(Api $api = null, $data = [])
    {
        $this->api = $api;
        $this->data = is_object($data) && $data instanceof Obj ? $data : new Obj($data ? (array)$data : []);
    }

    /**
     * @return Api|null
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    public function data()
    {
        return $this->data->data();
    }

    /**
     * @return array
     */
    public function getCreateDefaults()
    {
        return [];
    }

    /**
     * @param array $data
     *
     * @return Endpoint|$this
     */
    public function create($data = [])
    {
        return $this->postAndDataResponse(array_merge($this->getCreateDefaults(), $data), $this->path, $this->path);
    }

    public function all()
    {
        return $this->getAndDataResponse($this->path, $this->path);
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

        $this->data = new Obj($this->api->getApiResponse($key));

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
            $this->data = new Obj($this->api->getApiResponse($key));
        }

        return $this;
    }

    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data->data());
    }

    public function offsetUnset($offset)
    {
        unset($this->data->{$offset});
    }

    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    public function __get($key)
    {
        return $this->data->{$key} ?? null;
    }

    public function jsonSerialize(): mixed
    {
        return $this->data;
    }
}
