<?php

namespace PlacetoPay\MPI\Contracts;

interface MPIClient
{
    /**
     * Performs a HTTP request and returns the information on array
     * @param $url
     * @param $method
     * @param $data
     * @param $headers
     * @return array
     */
    public function execute($url, $method, $data, $headers);
}