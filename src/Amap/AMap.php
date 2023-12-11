<?php

namespace Ycstar\Multimap\Amap;

use GuzzleHttp\Client;
use Ycstar\Multimap\Contracts\DataArray;

class AMap
{
    public function __construct(array $options)
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
        $url = $this->config->get('host') . '/v5/direction/driving';

        $query = [
            'key' => $this->config->get('key'),
            'origin' => $origin,
            'destination' => $destination,
            'show_fields' => 'polyline, cost',
        ];

        $client = new Client();
        $response = $client->get($url, [
            'query' => array_merge($query, $ops),
        ]);

        return json_decode($response, true);

    }

}