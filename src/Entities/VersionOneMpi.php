<?php


namespace PlacetoPay\MPI\Entities;


use PlacetoPay\MPI\Constants\MPI;
use PlacetoPay\MPI\Messages\LookupResponseVersionOne;
use PlacetoPay\MPI\Messages\QueryResponseVersionOne;
use PlacetoPay\MPI\Requests\LookupRequestVersionOne;

class VersionOneMpi implements MpiContract
{
    public function lookup(array $data)
    {
        return new LookupRequestVersionOne($data) ;
    }

    public function lookupResponse(array $data)
    {
        return LookupResponseVersionOne::loadFromResult($data);
    }

    public function lookupEndpoint()
    {
        return MPI::LOOKUP_ENDPOINTS[MPI::VERSION_ONE] ;
    }

    public function queryEndpoint($id)
    {
        return MPI::QUERY_ENDPOINTS[MPI::VERSION_ONE].$id;
    }

    public function queryResponse(array $data, $id)
    {
        return QueryResponseVersionOne::loadFromResult($data, $id);
    }
}
