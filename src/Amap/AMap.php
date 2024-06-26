<?php

namespace Ycstar\Multimap\Amap;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;
use Ycstar\Multimap\Exceptions\InvalidArgumentException;

class AMap
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
        $url = $this->host . '/v3/geocode/regeo';

        $query = [
            'key' => $this->key,
            'location' => $location,
        ];

        $client = new Client();

        $response = $client->get($url, [
            'query' => array_merge($query, $ops),
        ])->getBody()->getContents();

        return json_decode($response, true);
    }

    public function drive($origin, $destination, array $ops = [])
    {
        $url = $this->host . '/v5/direction/driving';

        $query = [
            'key' => $this->key,
            'origin' => $origin,
            'destination' => $destination,
            'show_fields' => 'polyline,cost',
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
        $url = $this->host . '/v5/direction/driving';
        $client = new Client(['base_uri' => $url]);
        $promises = [];
        foreach ($array as $k => $v){
            $query = [
                'key' => $this->key,
                'origin' => $v['origin']??'',
                'destination' => $v['destination']??'',
                'show_fields' => 'polyline,cost',
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