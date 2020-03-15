<?php

namespace PlacetoPay\MPI\Constants;

interface Mpi
{
    const VERSION_ONE = 'V1';
    const VERSION_TWO = 'V2';

    const lookupEndPoint = [
      self::VERSION_ONE => 'api/lookup',
      self::VERSION_TWO => ''
    ];

    const queryEndPoint = [
        self::VERSION_ONE => '/api/transactions/',
        self::VERSION_TWO => ''
    ];
}