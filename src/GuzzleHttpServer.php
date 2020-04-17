<?php


namespace ChrisGuzzleHttp;


use ChrisGuzzleHttp\Exceptions\ComException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GuzzleHttpServer
{
    public static function request_http_get($uri, $option = [])
    {
        $client = new Client(['timeout' => 10.0]);

        try {
            $response = $client->request('get', $uri, $option);

            return json_decode((string)$response->getBody(), true);
        } catch (RequestException $e) {
            throw new ComException($e->getMessage(), $e->getCode());
        }
    }

    public static function request_http_post($uri, $params = [], $body_type = 'json')
    {
        $client = new Client(['timeout' => 10.0]);

        try {
            $post_data = [];

            if ($params) {
                if ($body_type === 'body') {
                    $post_data = [
                        'body' => json_encode($params, JSON_UNESCAPED_UNICODE)
                    ];
                }
                elseif($body_type === 'json') {
                    $post_data = [
                        'json' => $params
                    ];
                }
            }

            $response = $client->request('post', $uri, $post_data);

            $data = json_decode((string)$response->getBody(), true);

            return $data;
        } catch (RequestException $e) {
            throw new ComException($e->getMessage(), $e->getCode());
        }
    }
}