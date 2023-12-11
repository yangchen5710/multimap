<?php

namespace Ycstar\Multimap\Baidu;

use GuzzleHttp\Client;

class BMap
{
    protected $host;

    protected $key;

    public function __construct(array $options)
    {
        if (empty($options['host'])) {
            throw new InvalidArgumentException("Missing Config -- [host]");
        }

        if (empty($options['key'])) {
            throw new InvalidArgumentException("Missing Config -- [key]");
        }

        $this->host = $options['host'];
        $this->key = $options['key'];
    }

    public function drive($origin, $destination, $ops = [])
    {
        $url = $this->host . '/direction/v2/driving';

        $query = [
            'origin' => $origin,
            'destination' => $destination,
            'ak' => $this->key,
        ];

        $client = new Client();
        $response = $client->get($url, [
            'query' => array_merge($query, $ops),
        ])->getBody()->getContents();

        return json_decode($response, true);
    }

}