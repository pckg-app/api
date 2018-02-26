<?php namespace Pckg\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Psr7\Response;

abstract class Api
{

    /**
     * @var Promise
     */
    protected $response;

    protected $endpoint;

    protected $apiKey;

    protected $requestOptions;

    public function getApiResponse($key = null, $default = [])
    {
        $decoded = json_decode($this->response->getBody(), true);

        if ($key) {
            return $decoded[$key] ?? $default;
        }

        return $decoded ?? $default;
    }

    /**
     * @return Promise|Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function postApi($url, $data = [], $options = [])
    {
        return $this->request('POST', $url, array_merge(['form_params' => $data], $options));
    }

    public function getApi($url, $options = [])
    {
        return $this->request('GET', $url, $options);
    }

    protected function request($type, $url, $data = [])
    {
        $client = new Client();
        $this->response = $client->request(
            $type,
            $this->endpoint . $url,
            array_merge($this->requestOptions, $data)
        );

        return $this;
    }

}