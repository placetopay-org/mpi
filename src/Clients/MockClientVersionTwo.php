<?php


namespace PlacetoPay\MPI\Clients;


use PlacetoPay\MPI\Constants\MPI;
use PlacetoPay\MPI\Contracts\MPIClient;
use PlacetoPay\MPI\Contracts\MPIException;
use PlacetoPay\MPI\Exceptions\ErrorResultMPI;

class MockClientVersionTwo implements MPIClient
{

    /**
     * @inheritDoc
     * @throws MPIException
     */
    public function execute($url, $method, $data, $headers)
    {
        if (strpos($url, MPI::LOOKUP_ENDPOINTS[MPI::VERSION_TWO]) !== false) {
            return $this->lookup($url, $method, $data);
        } else {
            return $this->query($url, $method);
        }
    }

    /**
     * @param $url
     * @param $method
     * @param $data
     * @return array
     * @throws MPIException
     */
    public function lookup($url, $method, $data): array
    {
        if ($method != 'POST') {
            throw new MPIException("Incorrect HTTP Method {$method} ON {$url}");
        }

        switch ($data['acctNumber']) {
            case '4012000000001006':
                return [
                    'sessionToken' => rand(60, 60),
                    'redirectURL' => 'https://dnetix.co/ping/3ds',
                    'transactionID' => 1,
                ];
                break;
            case '4111111111111':
                $response = [
                        "error_number" => 1011,
                        "error_description" =>  "Invalid arguments to initiate the authentication request",
                        "errors" => [
                            "acctNumber"=> [
                                "The card number doesn't pass validation"
                            ]
                        ]
                ];
                throw new ErrorResultMPI($response['error_description'], $response['error_number']);
                break;
        }
    }

    /**
     * @param $url
     * @param $method
     * @return array|mixed
     * @throws MPIException
     */
    public function query($url, $method)
    {
        $id = explode('/', $url);
        $id = end($id);

        if ($method == 'GET') {
            switch ($id) {
                case 1:
                    return [
                        'transStatus' => 'Y',
                        'eci' => '07',
                        'acsTransID' => '37a7b6e0-fd58-4e38-98de-79c70c526a47',
                        'dsTransID' => 'de018c08-bd14-426a-9d52-46500a17091e=',
                        'threeDSServerTransID' => 'eadd3a60-b870-41d0-977f-921b3dbe6323/MkGJDl2Y5E=',
                        'authenticationValue' => 'AAABBZEEBgAAAAAAAAQGAAAAAAA='
                    ];
                    break;
            }
        }

        throw new MPIException("Incorrect HTTP Method {$method} ON {$url}");
    }
}