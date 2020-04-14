<?php

namespace PlacetoPay\MPI\Entities;

use PlacetoPay\MPI\Contracts\LookupResponse;
use PlacetoPay\MPI\Contracts\QueryResponse;
use PlacetoPay\MPI\Contracts\Request;

interface MpiContract
{
    /**
     * Instantiate the search method according to the version of 3ds.
     * @param  array  $data.
     * @return Request.
     */
    public function lookup(array $data): Request;

    /**
     * Return a lookup response handler according to the 3ds version.
     * @param  array  $data
     * @return LookupResponse
     */
    public function lookupResponse(array $data): LookupResponse;

    /**
     * @return string
     */
    public function lookupEndpoint(): string;

    /**
     * @param $id
     * @return string
     */
    public function queryEndpoint($id): string;

    /**
     * Return a query response handler according to the 3ds version.
     * @param  array  $data
     * @param $id
     * @return QueryResponse
     */
    public function queryResponse(array $data, $id): QueryResponse;
}
