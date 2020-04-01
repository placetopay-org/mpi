<?php

namespace PlacetoPay\MPI;

use PlacetoPay\MPI\Clients\GuzzleMPIClient;
use PlacetoPay\MPI\Constants\MPI;
use PlacetoPay\MPI\Contracts\Request;
use PlacetoPay\MPI\Contracts\MPIClient;
use PlacetoPay\MPI\Contracts\MPIException;
use PlacetoPay\MPI\Entities\MpiManager;
use PlacetoPay\MPI\Messages\LookUpResponseVersionOne;
use PlacetoPay\MPI\Messages\QueryResponseVersionOne;
use PlacetoPay\MPI\Messages\UpdateTransactionRequest;
use PlacetoPay\MPI\Messages\UpdateTransactionResponse;

class MPIService
{
    protected $url;
    protected $apiKey;
    /**
     * @var MPIClient
     */
    protected $client;
    protected $headers = [
        'Accept' => 'application/vnd.api.v1+json',
        'Content-Type' => 'application/json',
    ];

    /**
     * @var \PlacetoPay\MPI\Entities\MpiContract
     */
    private $versionDirector;

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

        if (isset($settings['3dsVersion'])) {
            $this->versionDirector = MpiManager::create($settings['3dsVersion']);
        } else {
            $this->versionDirector = MpiManager::create(MPI::VERSION_ONE);
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
     * @return LookUpResponseVersionOne
     * @throws \Exception
     */
    public function lookUp($data)
    {
        $url = $this->url($this->versionDirector->lookupEndpoint());

        $request = $this->versionDirector->lookup($data)->toArray();

        $method = 'POST';

        $this->addHeader('Authorization', 'Bearer ' . $this->apiKey);

        if (isset($data['userAgent'])) {
            $this->addHeader('User-Agent', $data['userAgent']);
        }

        $response = $this->client()->execute($url, $method, $request, $this->headers());
        return $this->versionDirector->lookupResponse($response);
    }

    /**
     * Check the status of the authentication
     * @param $id
     * @param array $additional
     * @return QueryResponseVersionOne
     * @throws \Exception
     */
    public function query($id, $additional = [])
    {
        $url = $this->url($this->versionDirector->queryEndpoint($id));
        $method = 'GET';

        $this->addHeader('Authorization', 'Bearer ' . $this->apiKey);

        if (isset($additional['userAgent'])) {
            $this->addHeader('User-Agent', $additional['userAgent']);
        }

        $response = $this->client()->execute($url, $method, [], $this->headers());
        return $this->versionDirector->queryResponse($response, $id);
    }

    public function update($id, UpdateTransactionRequest $request): UpdateTransactionResponse
    {
        $url = $this->url('/api/transactions/' . $id);
        $method = 'PATCH';

        $this->addHeader('Authorization', 'Bearer ' . $this->apiKey);

        $data = [
            'payment' => array_filter([
                'processor' => $request->processor(),
                'authorization' => $request->authorization(),
                'provider' => $request->provider(),
                'base24' => $request->base24(),
                'iso' => $request->iso(),
            ]),
        ];

        $response = $this->client()->execute($url, $method, $data, $this->headers());

        return UpdateTransactionResponse::loadFromResult($response, $id);
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

    protected function url($endpoint = '')
    {
        return $this->url . $endpoint;
    }

    protected function client()
    {
        return $this->client;
    }

    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    protected function headers()
    {
        return $this->headers;
    }
}
