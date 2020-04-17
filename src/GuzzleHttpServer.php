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

            return self::handle_response($response);
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
                elseif ($body_type === 'json') {
                    $post_data = [
                        'json' => $params
                    ];
                }
            }

            $response = $client->request('post', $uri, $post_data);

            return self::handle_response($response);
        } catch (RequestException $e) {
            throw new ComException($e->getMessage(), $e->getCode());
        }
    }

    protected static function handle_response($input)
    {
        if ($res = json_decode((string)$input->getBody(), true)) {
            return $res;
        }
        else {
            return (string)$input->getBody();
        }
    }
}