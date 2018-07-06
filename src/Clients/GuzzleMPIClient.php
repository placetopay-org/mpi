<?php


namespace PlacetoPay\MPI\Clients;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use PlacetoPay\MPI\Contracts\MPIClient;

class GuzzleMPIClient implements MPIClient
{

    /**
     * Performs a HTTP request and returns the information on array
     * @param $url
     * @param $method
     * @param $data
     * @param $headers
     * @return array
     * @throws \Exception
     */
    public function execute($url, $method, $data, $headers)
    {
        try {
            $client = new Client();
            if ($method == 'POST') {
                $response = $client->post($url, [
                    'headers' => $headers,
                    'json' => $data,
                ]);
            } else if ($method == 'GET') {
                $response = $client->get($url, [
                    'headers' => $headers,
                    'json' => $data,
                ]);
            } else if ($method == 'PUT') {
                $response = $client->put($url, [
                    'headers' => $headers,
                    'json' => $data,
                ]);
            } else {
                throw new \Exception("No valid method for this request");
            }

            $response = $response->getBody()->getContents();
            return json_decode($response, true);

        } catch (ClientException $e) {
            return json_decode($e->getResponse()->getBody()->getContents(), true);
        } catch (ServerException $e) {
            return json_decode($e->getResponse()->getBody()->getContents(), true);
        }
    }

}