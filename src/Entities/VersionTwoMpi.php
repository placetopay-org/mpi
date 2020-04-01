<?php


namespace PlacetoPay\MPI\Entities;


use PlacetoPay\MPI\Constants\MPI;
use PlacetoPay\MPI\Messages\LookupResponseVersionTwo;
use PlacetoPay\MPI\Messages\QueryResponseVersionTwo;
use PlacetoPay\MPI\Requests\LookUpVersionTwoRequest;

class VersionTwoMpi implements MpiContract
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

    public function lookupResponse(array $data)
    {
       return LookupResponseVersionTwo::loadFromResult($data);
    }

    public function queryResponse(array $data, $id)
    {
        return QueryResponseVersionTwo::loadFromResult($data, $id);
    }
}
