<?php namespace Pckg\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;

abstract class Api
{

    /**
     * @var Promise|Response
     */
    protected $response;

    protected $endpoint;

    protected $apiKey;

    protected $requestOptions;

    protected $client;

    protected $content;

    public function getApiResponse($key = null, $default = [])
    {
        if (!$this->response) {
            return null;
        }

        $decoded = json_decode($this->content, true);

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

    public function deleteApi($url, $options = [])
    {
        return $this->request('DELETE', $url, $options);
    }

    public function request($type, $url, $data = [])
    {
        $this->client = new Client();
        $this->content = null;

        /**
         * Set default request options.
         * Because we do not throw exception on http errors
         */
        $requestOptions = array_merge(array_merge([
            // RequestOptions::HTTP_ERRORS => false,
        ], $this->requestOptions), $data);

        /**
         * Save response.
         */
        $this->response = $this->client->request(
            $type,
            $this->endpoint . $url,
            $requestOptions
        );

        /**
         * Parse content.
         * (and code?)
         */
        $this->content = $this->response->getBody()->getContents();

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

}