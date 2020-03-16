<?php

namespace PlacetoPay\MPI\Entities;

interface Director
{
    public function lookup(array $data);
    public function lookupEndpoint();
    public function queryEndpoint($id);
}
