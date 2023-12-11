<?php

namespace Ycstar\Multimap\Baidu;

use GuzzleHttp\Client;
use Ycstar\Multimap\Contracts\DataArray;

class BMap
{
    public function __construct()
    {
        if (empty($options['host'])) {
            throw new InvalidArgumentException("Missing Config -- [host]");
        }

        if (empty($options['key'])) {
            throw new InvalidArgumentException("Missing Config -- [key]");
        }

        $this->config = new DataArray($options);
    }

    public function drive($origin, $destination, $ops = [])
    {
        $url = $this->config->get('host') . '/direction/v2/driving';

        $query = [
            'origin' => $origin,
            'destination' => $destination,
            'ak' => $this->config->get('key'),
        ];

        $client = new Client();
        $response = $client->get($url, [
            'query' => array_merge($query, $ops),
        ])->getBody()->getContents();

        return json_decode($response, true);
    }

}