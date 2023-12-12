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

    public function regeo(string $location, array $ops = [])
    {
        $url = $this->host . '/reverse_geocoding/v3/';

        list($lng, $lat) = explode(',', $location);

        $query = [
            'ak' => $this->key,
            'location' => $lat.','.$lng,
        ];

        $client = new Client();

        $response = $client->get($url, [
            'query' => array_merge($query, $ops),
        ])->getBody()->getContents();

        return json_decode($response, true);
    }

    public function drive($origin, $destination, array $ops = [])
    {
        $url = $this->host . '/direction/v2/driving';

        list($originLng, $originLat) = explode(',', $origin);
        list($destLng, $destLat) = explode(',', $destination);

        $query = [
            'ak' => $this->key,
            'origin' => $originLat.','.$originLng,
            'destination' =>  $destLat.','.$destLng,
        ];

        $client = new Client();
        $response = $client->get($url, [
            'query' => array_merge($query, $ops),
        ])->getBody()->getContents();

        return json_decode($response, true);
    }

}