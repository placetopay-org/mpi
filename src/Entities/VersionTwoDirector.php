<?php


namespace PlacetoPay\MPI\Entities;


use PlacetoPay\MPI\Constants\MPI;
use PlacetoPay\MPI\Requests\LookUpVersionTwoRequest;

class VersionTwoDirector implements Director
{
    public function lookup(array $data)
    {
        return new LookUpVersionTwoRequest($data);
    }

    public function lookupEndpoint()
    {
        return MPI::LOOKUP_ENDPOINTS[MPI::VERSION_TWO] ;
    }

    public function queryEndpoint($id)
    {
        return MPI::QUERY_ENDPOINTS[MPI::VERSION_TWO].$id;
    }
}
