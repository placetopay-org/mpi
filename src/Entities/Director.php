<?php

namespace PlacetoPay\MPI\Entities;

interface Director
{
    public function lookup(array $data);
    public function lookupEndpoint();
    public function queryEndpoint($id);
    public function lookupResponse(array $data);
    public function queryResponse(array $data, $id);
}
