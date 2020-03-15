<?php

namespace PlacetoPay\MPI\Entities;

use PlacetoPay\MPI\Constants\Mpi;
use PlacetoPay\MPI\Contracts\MPIFactory;

class Director
{
    /**
     * @var MPIFactory
     */
    private $instance;

    public function __construct($data, $threedsVersion)
    {
        $instance = $this->resolveInstance($threedsVersion);
        $this->instance = new $instance($data);
    }

    public function resolveInstance($threedsVersion)
    {
        switch ($threedsVersion){
            case Mpi::VERSION_TWO:
                return threedsVersionTwo::class;
                break;
            default:
                return threedsVersionOne::class;
        }
    }

    public function queryEndPoint()
    {
        return $this->instance->queryEndPoint();
    }

    public function toArray()
    {
        return $this->instance->toArray();
    }
}