<?php

namespace Pckg\Api\Afterware;

use Pckg\Api\Record\ApiLog;
use Pckg\Api\Record\RequestLog;
use Pckg\Framework\Request;
use Pckg\Framework\Response;

class LogResponses
{
    protected Request $request;

    protected Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function execute(callable $next)
    {
        if ($this->shouldLog()) {
            $this->logResponse();
        }

        return $next();
    }

    public function shouldLog()
    {
        $urls = config('pckg-app.api.log.response.include', []);

        return collect($urls)->has(fn($url) => strpos($this->request->url(), $url) === 0);
    }

    public function logResponse()
    {
        ApiLog::create([
            'type' => 'response:' . $this->response->getStatusCode(),
            'created_at' => date('Y-m-d H:i:s'),
            'data' => $this->response->getOutput(),
            'ip' => $this->request->clientIp(),
            'url' => $this->request->url(),
        ]);
    }
}
