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

    const API_KEY_HEADER = 'X-API-Key';

    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function setApiKey(?string $apiKey): self
    {
        $this->apiKey = $apiKey;
        $this->requestOptions[RequestOptions::HEADERS][static::API_KEY_HEADER] = $apiKey;

        return $this;
    }

    public function getApiResponse($key = null, $default = [])
    {
        if (!$this->response) {
            return null;
        }

        $decoded = json_decode($this->content, true);

        if ($key) {
            return $key === true ? $decoded : ($decoded[$key] ?? $default);
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
        return $this->request('POST', $url, !isset($options['multipart']) ? array_merge([RequestOptions::JSON => $data], $options) : $options);
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
        $finalUrl = (substr($url, 0, 1) === '/' ? str_replace('/api/', '', $this->endpoint) : $this->endpoint) . $url;
        $this->response = $this->client->request(
            $type,
            $finalUrl,
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
