<?php namespace Pckg\Api\Middleware;

use Pckg\Api\Record\ApiLog;
use Pckg\Api\Record\RequestLog;
use Pckg\Framework\Request;

class LogRequests
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function execute(callable $next)
    {
        if ($this->shouldLog()) {
            $this->logRequest();
        }

        return $next();
    }

    public function shouldLog()
    {
        $urls = config('pckg-app.api.log.request.includes', []);

        return collect($urls)->has(fn($url) => strpos($url, $this->request->url()) === 0);
    }

    public function logRequest()
    {
        ApiLog::create([
            'type' => 'request:' . $this->request->getMethod(),
            'created_at' => date('Y-m-d H:i:s'),
            'data' => post()->all(),
            'ip' => $this->request->clientIp(),
            'url' => $this->request->url(),
        ]);
    }
}
