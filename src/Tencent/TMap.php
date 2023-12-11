<?php

namespace Ycstar\Multimap\Tencent;

use GuzzleHttp\Client;

class TMap
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

    public function drive($origin, $destination, array $ops = [])
    {
        $url = $this->host . '/ws/direction/v1/driving/';

        list($originLng, $originLat) = explode(',', $origin);
        list($destLng, $destLat) = explode(',', $destination);

        $query = [
            'from' => $originLat.','.$originLng,
            'to' =>  $destLat.','.$destLng,
            'key' => $this->key,
        ];

        $client = new Client();
        $response = $client->get($url, [
            'query' => array_merge($query, $ops),
        ])->getBody()->getContents();

        return json_decode($response, true);
    }

}