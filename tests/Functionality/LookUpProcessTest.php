<?php

namespace PlacetoPay\MPI\Tests\Functionality;

use PlacetoPay\MPI\MPIService;
use PlacetoPay\MPI\Tests\BaseTestCase;

class LookUpProcessTest extends BaseTestCase
{
    public function create($overwrite = [])
    {
        return new MPIService(array_merge([
            'url' => getenv('MPI_URL'),
            'apiKey' => getenv('MPI_API_KEY'),
            'client' => new \PlacetoPay\MPI\Clients\MockClient(),
        ], $overwrite));
    }

    public function testItConstructTheEntityCorrectly()
    {
        $mpi = $this->create();

        $response = $mpi->lookUp([
            'card' => [
                'number' => '4532840681197602',
                'expirationYear' => '20',
                'expirationMonth' => '12',
            ],
            'amount' => 1200,
            'currency' => 'COP',
            'redirectUrl' => 'https://dnetix.co/ping/3ds',
        ]);

        $this->assertTrue($response->canAuthenticate());
        $this->assertEquals(1, $response->identifier());
        $this->assertEquals('https://dnetix.co/ping/3ds', $response->processUrl());
    }

    /**
     * @expectedException \PlacetoPay\MPI\Contracts\MPIException
     */
    public function testItFailsIfNotURLProvided()
    {
        $this->create(['url' => null]);
    }

    public function testItInstantiateTheGuzzleLibrary()
    {
        $mpiInstance = $this->create(['client' => null]);
        $this->assertInstanceOf(MPIService::class, $mpiInstance);
    }

    public function testItSendsTheInstallmentsCorrectly()
    {
        $mpi = $this->create();

        $response = $mpi->lookUp([
            'card' => [
                'number' => '4716036206946551',
                'expirationYear' => '20',
                'expirationMonth' => '12',
                'installments' => 3,
            ],
            'amount' => 1200,
            'currency' => 'COP',
            'redirectUrl' => 'https://dnetix.co/ping/3ds',
        ]);

        $this->assertFalse($response->canAuthenticate(), "Card is not registered");
    }

    /**
     * @expectedException \PlacetoPay\MPI\Contracts\MPIException
     */
    public function testItValidatesTheInstallmentsCorrectly()
    {
        $mpi = $this->create();

        $response = $mpi->lookUp([
            'card' => [
                'number' => '4716036206946551',
                'expirationYear' => '20',
                'expirationMonth' => '12',
            ],
            'amount' => 1200,
            'currency' => 'COP',
            'redirectUrl' => 'https://dnetix.co/ping/3ds',
        ]);

        $this->assertFalse($response->canAuthenticate(), "Card is not registered");
    }

    public function testItChangesTheApiKeyOnDemand()
    {
        $mpi = $this->create();

        $mpi->setApiKey('VALID_ONE');

        $response = $mpi->lookUp([
            'card' => [
                'number' => '6011499026766178',
                'expirationYear' => '20',
                'expirationMonth' => '12',
            ],
            'amount' => 1200,
            'currency' => 'COP',
            'redirectUrl' => 'https://dnetix.co/ping/3ds',
        ]);

        $this->assertFalse($response->canAuthenticate());
    }

    /**
     * @expectedException \PlacetoPay\MPI\Contracts\MPIException
     */
    public function testItChangesTheApiKeyOnDemandInvalid()
    {
        $mpi = $this->create();

        $mpi->setApiKey('INVALID_ONE');

        $response = $mpi->lookUp([
            'card' => [
                'number' => '6011499026766178',
                'expirationYear' => '20',
                'expirationMonth' => '12',
            ],
            'amount' => 1200,
            'currency' => 'COP',
            'redirectUrl' => 'https://dnetix.co/ping/3ds',
        ]);

        $this->assertFalse($response->canAuthenticate());
    }

    public function testItSendsCorrectlyTheRedirectUrl()
    {
        $mpi = $this->create();

        $response = $mpi->lookUp([
            'card' => [
                'number' => '5554575520765108',
                'expirationYear' => '20',
                'expirationMonth' => '12',
            ],
            'amount' => 1200,
            'currency' => 'COP',
            'redirectUrl' => 'https://example.com/return',
        ]);

        $this->assertFalse($response->canAuthenticate());
        $this->assertEquals('07', $response->eci());
    }

    /**
     * @expectedException \PlacetoPay\MPI\Contracts\MPIException
     */
    public function testItValidatesCorrectlyTheRedirectUrl()
    {
        $mpi = $this->create();

        $response = $mpi->lookUp([
            'card' => [
                'number' => '5554575520765108',
                'expirationYear' => '20',
                'expirationMonth' => '12',
            ],
            'amount' => 1200,
            'currency' => 'COP',
            'redirectUrl' => 'https://other.com/return',
        ]);

        $this->assertFalse($response->canAuthenticate());
    }
}