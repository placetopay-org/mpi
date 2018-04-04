<?php


namespace PlacetoPay\MPI;


use PlacetoPay\MPI\Clients\GuzzleMPIClient;
use PlacetoPay\MPI\Contracts\MPIClient;
use PlacetoPay\MPI\Contracts\MPIException;
use PlacetoPay\MPI\Messages\LookUpResponse;
use PlacetoPay\MPI\Messages\QueryResponse;

class MPIService
{
    private $url;
    private $apiKey;
    /**
     * @var MPIClient
     */
    private $client;

    /**
     * MPIService constructor.
     * @param $settings
     * @throws MPIException
     */
    public function __construct($settings)
    {
        if (!isset($settings['url']) || !filter_var($settings['url'], FILTER_VALIDATE_URL)) {
            throw new MPIException('URL is required in order to instantiate the service');
        }

        if (isset($settings['apiKey'])) {
            $this->apiKey = $settings['apiKey'];
        }

        if (isset($settings['client']) && $settings['client'] instanceof MPIClient) {
            $this->client = $settings['client'];
        } else {
            $this->client = new GuzzleMPIClient();
        }

        $this->url = $settings['url'];
    }

    /**
     * Performs the query to know if the card can be authenticated
     * @param $data
     * @return LookUpResponse
     */
    public function lookUp($data)
    {
        $url = $this->url() . '/api/lookup';
        $method = 'POST';
        $request = [
            'pan' => $data['card']['number'],
            'expiration_year' => $data['card']['expirationYear'],
            'expiration_month' => $data['card']['expirationMonth'],
            'amount' => $data['amount'],
            'redirect_uri' => $data['redirectUrl'],
        ];
        if (isset($data['card']['installments'])) {
            $request['installments'] = $data['card']['installments'];
        }

        $response = $this->client()->execute($url, $method, $request, $this->headers());
        return LookUpResponse::loadFromResult($response);
    }

    /**
     * Check the status of the authentication
     * @param $id
     * @return QueryResponse
     * @throws Exceptions\ErrorResultMPI
     */
    public function query($id)
    {
        $url = $this->url() . '/api/transactions/' . $id;
        $method = 'GET';

        $response = $this->client()->execute($url, $method, [], $this->headers());
        return QueryResponse::loadFromResult($response);
    }

    /**
     * Allows to change the API KEY without the need to instantiate another service
     * @param $apiKey
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    protected function url()
    {
        return $this->url;
    }

    protected function client()
    {
        return $this->client;
    }

    protected function headers()
    {
        return [
            'Accept' => 'application/vnd.api.v1+json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ];
    }

}