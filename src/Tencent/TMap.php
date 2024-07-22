<?php

namespace Ycstar\Multimap\Tencent;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;
use Ycstar\Multimap\Exceptions\InvalidArgumentException;

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

    public function regeo(string $location, array $ops = [])
    {
        $url = $this->host . '/ws/geocoder/v1/';
        list($lng, $lat) = explode(',', $location);

        $query = [
            'key' => $this->key,
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
        $url = $this->host . '/ws/direction/v1/driving/';

        list($originLng, $originLat) = explode(',', $origin);
        list($destLng, $destLat) = explode(',', $destination);

        $query = [
            'key' => $this->key,
            'from' => $originLat.','.$originLng,
            'to' =>  $destLat.','.$destLng,
        ];

        $client = new Client();
        $response = $client->get($url, [
            'query' => array_merge($query, $ops),
        ])->getBody()->getContents();

        return json_decode($response, true);
    }

    public function driveMulti(array $array)
    {
        if (empty($array)) {
            throw new InvalidArgumentException("multi request require a not empty array");
        }
        $url = $this->host . '/ws/direction/v1/driving/';
        $client = new Client(['base_uri' => $url]);
        $promises = [];
        foreach ($array as $k => $v){
            list($originLng, $originLat) = explode(',', $v['origin']);
            list($destLng, $destLat) = explode(',', $v['destination']);
            $query = [
                'key' => $this->key,
                'from' => $originLat.','.$originLng,
                'to' => $destLat.','.$destLng,
            ];
            $promises[$k] = $client->getAsync('',[
                'query' => array_merge($query, $v['ops']??[]),
            ]);
        }
        $results = Utils::unwrap($promises);
        $responses = [];
        foreach ($results as $k => $result){
            $response = $result->getBody()->getContents();
            $responses[$k] = json_decode($response, true);
        }
        return $responses;
    }

}