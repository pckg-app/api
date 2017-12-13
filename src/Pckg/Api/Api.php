<?php namespace Pckg\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Promise;

class Api
{

    /**
     * @var Promise
     */
    protected $response;

    public function getApiResponse($key = null, $default = [])
    {
        $decoded = json_decode($this->response->getBody(), true);

        if ($key) {
            return $decoded[$key] ?? $default;
        }

        return $decoded ?? $default;
    }

    public function postApi($url, $data = [], $options = [])
    {
        return $this->request('POST', $url, array_merge(['form_params' => $data], $options));
    }

    public function getApi($url, $data = [])
    {
        return $this->request('GET', $url);
    }

    protected function request($type, $url, $data = [])
    {
        $client = new Client();
        $this->response = $client->request(
            $type,
            $this->getRequestEndpoint() . $url,
            array_merge($this->getRequestOptions(), $data)
        );

        return $this;
    }

    protected function getRequestEndpoint()
    {
        return '';
    }

    protected function getRequestOptions()
    {
        return [];
    }

}