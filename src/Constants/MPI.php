<?php

namespace PlacetoPay\MPI\Constants;

interface MPI
{
    const VERSION_ONE = 'V1';
    const VERSION_TWO = 'V2';

    const LOOKUP_ENDPOINTS = [
      self::VERSION_ONE => '/api/lookup',
      self::VERSION_TWO => '/api/threeds/v2/sessions'
    ];

    const QUERY_ENDPOINTS = [
        self::VERSION_ONE => '/api/transactions/',
        self::VERSION_TWO => '/api/threeds/v2/transactions/'
    ];


    const PAYMENT_TX_CODE = '01';
    const RECURRING_TX_CODE = '02';
    const INSTALLMENT_TX_CODE = '03';

    const THREEDS_AUTH_IND = [
        self::RECURRING_TX_CODE,
        self::INSTALLMENT_TX_CODE
    ];
}
