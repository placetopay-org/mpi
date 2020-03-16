<?php

namespace PlacetoPay\MPI\Constants;

interface MPI
{
    const VERSION_ONE = 'V1';
    const VERSION_TWO = 'V2';

    const LOOKUP_ENDPOINTS = [
      self::VERSION_ONE => 'api/lookup',
      self::VERSION_TWO => ''
    ];

    const QUERY_ENDPOINTS = [
        self::VERSION_ONE => '/api/transactions/',
        self::VERSION_TWO => ''
    ];
}
