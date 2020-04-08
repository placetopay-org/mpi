<?php


namespace PlacetoPay\MPI\Clients;


use PlacetoPay\MPI\Constants\MPI;
use PlacetoPay\MPI\Contracts\MPIClient;
use PlacetoPay\MPI\Contracts\MPIException;

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
                return [
                        "error_number" => 1011,
                        "error_description" =>  "Invalid arguments to initiate the authentication request",
                        "errors" => [
                            "acctNumber"=> [
                                "The card number doesn't pass validation"
                            ]
                        ]
                ];
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
                case 2:
                    return [
                        "transStatus" => "U",
                        "transStatusReason" => "22",
                        "eci" => "07",
                        "acsTransID" => "155222d5-3933-475b-a153-db899eee38b2",
                        "dsTransID" => "51e8bbbb-5316-4a6b-a301-726a46a02dc5",
                        "threeDSServerTransID" => "515ba5ef-100e-4040-8028-df915f9fcdab"
                    ];
                case 3:
                    return [
                        "transStatus" => "N",
                        "transStatusReason" => "01",
                        "eci" => "07",
                        "acsTransID" => "52afc3e7-84fd-4420-bdc4-236901fbf09f",
                        "dsTransID" => "e11cb28d-470b-4a7b-96ca-86ad813fa16d",
                        "threeDSServerTransID" => "d27082af-6c3f-4664-95a2-319b07f1ba8b"
                    ];
                case 4:
                    return [
                        "transStatus" => "R",
                        "transStatusReason" => "11",
                        "eci" => "07",
                        "acsTransID" => "fb068cda-a161-4c8c-86e9-78433f3b69c0",
                        "dsTransID" => "38148c14-9cc6-4e91-a78a-005d7b6f8d51",
                        "threeDSServerTransID" => "728ac7b6-136b-4a5f-a32a-04bab37b5796"
                    ];
                case 5:
                    return [
                        "transStatus" => "I",
                        "eci" => "07",
                        "acsTransID" => "5c5814d5-8cdd-44b2-bd1c-3f50b7e8e699",
                        "dsTransID" => "dee10bd1-7139-46c4-a5ec-40948af1c591",
                        "threeDSServerTransID" => "d98a3261-7da3-4b1a-98cc-e14a07edfdc6",
                        "authenticationValue" => "AAABBZEEBgAAAAAAAAQGAAAAAAA="
                    ];
                case 6:
                    return [
                        "transStatus" => "A",
                        "eci" => "07",
                        "acsTransID" => "42e4727a-77ac-44a3-8b8c-c58b48e48cc8",
                        "dsTransID" => "a59c5a5d-f041-4c23-ad22-eca4ee9060b6",
                        "threeDSServerTransID" => "b5723933-f822-43af-b82f-b48b4983da81",
                        "authenticationValue" => "AAABBZEEBgAAAAAAAAQGAAAAAAA="
                    ];
            }
        }

        throw new MPIException("Incorrect HTTP Method {$method} ON {$url}");
    }
}