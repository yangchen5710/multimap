<?php

namespace Ycstar\Multimap\Tencent;

use GuzzleHttp\Client;
use Ycstar\Multimap\Contracts\DataArray;

class TMap
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
        $url = $this->config->get('host') . '/ws/direction/v1/driving/';

        $query = [
            'from' => $origin,
            'to' => $destination,
            'key' => $this->config->get('key'),
        ];

        $client = new Client();
        $response = $client->get($url, [
            'query' => array_merge($query, $ops),
        ])->getBody()->getContents();

        return json_decode($response, true);

    }

}