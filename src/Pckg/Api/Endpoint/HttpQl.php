<?php namespace Pckg\Api\Endpoint;

use GuzzleHttp\RequestOptions;
use Pckg\Api\Endpoint;
use Pckg\Api\Query;

/**
 * Trait HttpQl
 * @package Pckg\Api\Endpoint
 */
trait HttpQl
{

    /**
     * @var Query
     */
    protected $query;

    /**
     * @return Query
     */
    public function getQuery()
    {
        /**
         * @var $this Endpoint
         */
        if (!$this->query) {
            $this->query = new Query($this);
        }

        return $this->query;
    }

    /**
     * @return mixed
     */
    public function save()
    {
        if ($this->data->id) {
            return $this->update();
        }

        return $this->insert();
    }

    /**
     * @return $this
     */
    public function update()
    {
        $this->getQuery()->set($this->data)->where('id', $this->data->id)->update();

        return $this;
    }

    /**
     * @param $data
     * @return $this|null
     */
    public function create($data = [])
    {
        return $this->getQuery()->set(array_merge($this->getCreateDefaults(), $data))->insert();
    }

    /**
     * @return $this
     */
    public function insert()
    {
        $this->getQuery()->set($this->data)->insert();

        return $this;
    }

    /**
     * @return $this
     */
    public function delete()
    {
        $this->getQuery()->where('id', $this->data->id)->delete();

        return $this;
    }

    /**
     * @return mixed|\Pckg\Collection
     */
    public function all()
    {
        return $this->getQuery()->all();
    }

    /**
     * @return mixed|null
     */
    public function one()
    {
        return $this->getQuery()->one();
    }

    public function oneOrCreate(array $data = [])
    {
        return $this->getQuery()->oneOrCreate($data);
    }

    /**
     * @param string $field
     * @param string $source
     * @param string|null $destination
     */
    public function upload(string $field, string $source, string $destination = null)
    {
        $this->api->request('POST', 'http-ql?path=' . $this->path . '&record=' . $this->id . '&field=' . $field, [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => file_get_contents($source),
                    'filename' => $destination
                ],
                [
                    'name' => 'info',
                    'contents' => json_encode([
                        'final' => $destination,
                    ]),
                    'filename' => 'info.json',
                ]
            ],
        ]);

        $content = $this->api->getContent();
        d($content);
        $decoded = json_decode($content);

        if (!$decoded->success) {
            throw new \Exception($decoded->message ?? 'Error uploading file');
        }

        return $decoded->filename;
    }

}