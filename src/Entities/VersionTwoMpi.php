<?php


namespace PlacetoPay\MPI\Entities;


use PlacetoPay\MPI\Constants\MPI;
use PlacetoPay\MPI\Contracts\LookupResponse;
use PlacetoPay\MPI\Contracts\QueryResponse;
use PlacetoPay\MPI\Contracts\Request;
use PlacetoPay\MPI\Messages\LookupResponseVersionTwo;
use PlacetoPay\MPI\Messages\QueryResponseVersionTwo;
use PlacetoPay\MPI\Requests\LookupRequestVersionTwo;

class VersionTwoMpi implements MpiContract
{
    public function lookup(array $data): Request
    {
        return new LookupRequestVersionTwo($data);
    }

    public function lookupResponse(array $data): LookupResponse
    {
        return LookupResponseVersionTwo::loadFromResult($data);
    }

    public function lookupEndpoint()
    {
        return MPI::LOOKUP_ENDPOINTS[MPI::VERSION_TWO] ;
    }

    public function queryEndpoint($id)
    {
        return MPI::QUERY_ENDPOINTS[MPI::VERSION_TWO].$id;
    }

    public function queryResponse(array $data, $id): QueryResponse
    {
        return QueryResponseVersionTwo::loadFromResult($data, $id);
    }
}
