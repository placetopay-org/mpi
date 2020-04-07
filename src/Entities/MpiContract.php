<?php

namespace PlacetoPay\MPI\Entities;

interface MpiContract
{
    public function lookup(array $data);
    public function lookupResponse(array $data);
    public function lookupEndpoint();
    public function queryEndpoint($id);
    public function queryResponse(array $data, $id);
}
