<?php


namespace PlacetoPay\MPI\Clients;


use PlacetoPay\MPI\Contracts\MPIClient;
use PlacetoPay\MPI\Contracts\MPIException;

class MockClient implements MPIClient
{

    /**
     * Performs a HTTP request and returns the information on array
     * @param $url
     * @param $method
     * @param $data
     * @param $headers
     * @return array
     */
    public function execute($url, $method, $data, $headers)
    {
        if (strpos($url, 'lookup') !== false) {
            if ($method != 'POST') {
                throw new MPIException("Incorrect HTTP Method {$method} ON {$url}");
            }

            switch ($data['pan']) {
                case "4532840681197602":
                    return [
                        'enrolled' => 'Y',
                        'redirect_url' => 'https://dnetix.co/ping/3ds',
                        'transaction_id' => 1,
                    ];
                    break;
                case "4716036206946551":
                    if (!isset($data['installments']) || $data['installments'] > 36) {
                        throw new MPIException('Installments are not provided');
                    }
                    break;
                case "5554575520765108":
                    if ($data['redirect_uri'] != 'https://example.com/return') {
                        throw new MPIException('Redirect URL does not match');
                    }
                    return [
                        'enrolled' => 'N',
                        'eci_flag' => '07',
                    ];
                case "6011499026766178":
                    if ($headers['Authorization'] != "Bearer VALID_ONE") {
                        throw new MPIException('Api Key is not VALID_ONE');
                    }
                    break;
            }
            return [
                'enrolled' => 'N',
                'eci_flag' => '07',
            ];
        } else {
            if ($method != 'GET') {
                throw new MPIException("Incorrect HTTP Method {$method} ON {$url}");
            }

            $id = explode('/', $url);
            $id = end($id);

            switch ($id) {
                case 1:
                    return [
                        'authentication_status' => 'Y',
                        'validated_signature' => '1',
                        'eci' => '05',
                        'cavv' => 'AAACCZJiUGVlF4U5AmJQEwAAAAA=',
                        'xid' => 'Z8UuHYF8Epz46M8V/MkGJDl2Y5E=',
                    ];
                    break;
                case 2:
                    return [
                        'authentication_status' => 'A',
                        'validated_signature' => '1',
                        'eci' => '06',
                        'cavv' => 'CAACAlRGNFVVBEYZGUY0EwAAAAA=',
                        'xid' => '0CI2blBv4uSnIqelFXJX0mV+fMg=',
                    ];
                    break;
                case 3:
                    return [
                        'authentication_status' => 'Y',
                        'validated_signature' => null,
                        'eci' => '05',
                        'cavv' => 'AAACA1aTWUhYcxeGg5NZEAAAEAA=',
                        'xid' => 'UbRrlDARTXFT8GVALigF4MDyhkk=',
                    ];
                    break;
            }
        }
    }

}