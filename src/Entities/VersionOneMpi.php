<?php


namespace PlacetoPay\MPI\Entities;


use PlacetoPay\MPI\Constants\MPI;
use PlacetoPay\MPI\Contracts\LookupResponse;
use PlacetoPay\MPI\Contracts\QueryResponse;
use PlacetoPay\MPI\Contracts\Request;
use PlacetoPay\MPI\Messages\LookupResponseVersionOne;
use PlacetoPay\MPI\Messages\QueryResponseVersionOne;
use PlacetoPay\MPI\Requests\LookupRequestVersionOne;

class VersionOneMpi implements MpiContract
{
    public function lookup(array $data): Request
    {
        return new LookupRequestVersionOne($data) ;
    }

    public function lookupResponse(array $data): LookupResponse
    {
        return LookupResponseVersionOne::loadFromResult($data);
    }

    public function lookupEndpoint(): string
    {
        return MPI::LOOKUP_ENDPOINTS[MPI::VERSION_ONE] ;
    }

    public function queryEndpoint($id): string
    {
        return MPI::QUERY_ENDPOINTS[MPI::VERSION_ONE].$id;
    }

    public function queryResponse(array $data, $id): QueryResponse
    {
        return QueryResponseVersionOne::loadFromResult($data, $id);
    }
}
