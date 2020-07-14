<?php namespace Pckg\Api;

use GuzzleHttp\RequestOptions;
use Pckg\Framework\Helper\TryCatch;

class Query
{

    /**
     * @var Endpoint
     */
    protected $endpoint;

    /**
     * @var array
     */
    protected $where = [];

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var string
     */
    protected $sort;

    /**
     * @var string
     */
    protected $sortDirection;

    protected $set = [];

    /**
     * Query constructor.
     * @param Endpoint $endpoint
     */
    public function __construct(Endpoint $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @param $set
     * @return $this
     */
    public function set($set)
    {
        $this->set = $set;

        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @param string $comparator
     * @return $this
     */
    public function where($key, $value = true, $comparator = '=')
    {
        if (is_array($key)) {
            foreach ($key as $field => $value) {
                $this->where($field, $value);
            }

            return $this;
        }
        $this->where[] = [
            'k' => $key,
            'v' => $value,
            'c' => $comparator,
        ];

        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param int $page
     * @return $this
     */
    public function page(int $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @param string $field
     * @param string $direction
     * @return $this
     */
    public function orderBy(string $field, string $direction)
    {
        $this->sort = $field;
        $this->sortDirection = $direction;

        return $this;
    }

    /**
     * @return $this|TryCatch
     */
    public function try()
    {
        return new TryCatch($this);
    }

    /**
     * @return mixed|\Pckg\Collection
     */
    public function all()
    {
        $endpoint = $this->endpoint;
        $api = $endpoint->getApi();

        $api->request('SEARCH', 'http-ql?path=' . $endpoint->getPath(), [
            RequestOptions::JSON => $this->getRequestOptions([
                'X-Pckg-Orm-Action' => 'getAll',
            ]),
        ]);
        $data = json_decode($api->getContent());

        return collect($data->records ?? [])/*->map(function ($news) use ($endpoint, $api) {
            return new $endpoint($api, $news);
        })*/ ->setTotal($data->paginator->total ?? null);
    }

    /**
     * @return mixed|null
     * @throws \Exception
     */
    public function one()
    {
        $endpoint = $this->endpoint;
        $api = $endpoint->getApi();

        $api->request('SEARCH', 'http-ql?path=' . $endpoint->getPath() . '&getter=one', [
            RequestOptions::JSON => $this->getRequestOptions([
                'X-Pckg-Orm-Action' => 'getOne',
            ]),
        ]);

        $data = json_decode($api->getContent());

        if (!$data->record) {
            return null;
        }

        return new $endpoint($api, $data->record);
    }

    /**
     * @return mixed|null
     */
    public function oneOrCreate()
    {
        $endpoint = $this->endpoint;
        $api = $endpoint->getApi();

        $api->request('SEARCH', 'http-ql?path=' . $endpoint->getPath() . '&getter=one', [
            RequestOptions::JSON => $this->getRequestOptions([
                'X-Pckg-Orm-Action' => 'getOrCreate',
            ]),
        ]);

        $data = json_decode($api->getContent());

        if (!$data->record) {
            throw new \Exception('Record was not created?');
        }

        return new $endpoint($api, $data->record);
    }

    /**
     * @param array $default
     * @return array
     */
    protected function getRequestOptions($default = [])
    {
        return array_merge([
            'X-Pckg-Orm-Meta' => [], // additional data, for relations
            'X-Pckg-Orm-Search' => [], // full-text search
            'X-Pckg-Orm-Filters' => json_encode($this->where), // no by default
            'X-Pckg-Orm-Fields' => [], // all non-computable by default
            'X-Pckg-Orm-Paginator' => json_encode([
                'page' => $this->page ?? 1,
                'limit' => $this->limit ?? 50,
                'sort' => $this->sort ?? null, // id
                'dir' => $this->sortDirection ?? null, // DESC
            ]),
        ], $default);
    }

    /**
     * @return |null
     */
    public function insert()
    {
        $endpoint = $this->endpoint;
        $api = $endpoint->getApi();

        $api->request('PUT', 'http-ql?path=' . $endpoint->getPath(), [
            RequestOptions::JSON => array_merge([
                'X-Pckg-Orm-Action' => 'insert',
                'X-Pckg-Orm-Meta' => [], // additional data, for relations
                'X-Pckg-Orm-Search' => [], // full-text search
                'X-Pckg-Orm-Filters' => json_encode($this->where), // no by default
                'X-Pckg-Orm-Fields' => [], // all non-computable by default
                'X-Pckg-Orm-Paginator' => json_encode([
                    'sort' => $this->sort ?? null, // id
                    'dir' => $this->sortDirection ?? null, // DESC
                ]),
            ], $this->set),
        ]);
        $data = json_decode($api->getContent());

        $record = $data->record ?? null;

        if (!$record) {
            throw new \Exception('Record was not created');
        }

        return new $endpoint($api, $record);
    }

    /**
     * @return bool
     */
    public function update()
    {
        $endpoint = $this->endpoint;
        $api = $endpoint->getApi();

        $api->request('PATCH', 'http-ql?path=' . $endpoint->getPath(), [
            RequestOptions::JSON => [
                'X-Pckg-Orm-Action' => 'update',
                'X-Pckg-Orm-Meta' => [], // additional data, for relations
                'X-Pckg-Orm-Search' => [], // full-text search
                'X-Pckg-Orm-Filters' => json_encode($this->where), // no by default
                'X-Pckg-Orm-Fields' => [], // all non-computable by default
                'X-Pckg-Orm-Paginator' => json_encode([
                    'sort' => $this->sort ?? null, // id
                    'dir' => $this->sortDirection ?? null, // DESC
                ]),
            ],
        ]);
        $data = json_decode($api->getContent());

        return $data->success ?? false;
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $endpoint = $this->endpoint;
        $api = $endpoint->getApi();

        $api->request('DELETE', 'http-ql?path=' . $endpoint->getPath(), [
            RequestOptions::JSON => [
                'X-Pckg-Orm-Action' => 'deleteOne?',
                'X-Pckg-Orm-Meta' => [], // additional data, for relations
                'X-Pckg-Orm-Search' => [], // full-text search
                'X-Pckg-Orm-Filters' => json_encode($this->where), // no by default
                'X-Pckg-Orm-Paginator' => json_encode([
                    'sort' => $this->sort ?? null, // id
                    'dir' => $this->sortDirection ?? null, // DESC
                ]),
            ],
        ]);
        $data = json_decode($api->getContent());

        return $data->success ?? false;
    }

}