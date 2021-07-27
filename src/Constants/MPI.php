<?php

namespace PlacetoPay\MPI\Constants;

interface MPI
{
    const VERSION_ONE = 'v1';
    const VERSION_TWO = 'v2';

    const LOOKUP_ENDPOINTS = [
      self::VERSION_ONE => '/api/lookup',
      self::VERSION_TWO => '/api/threeds/v2/sessions',
    ];

    const QUERY_ENDPOINTS = [
        self::VERSION_ONE => '/api/transactions/',
        self::VERSION_TWO => '/api/threeds/v2/transactions/',
    ];

    const PAYMENT_TRANSACTION_CODE = '01';
    const RECURRING_TRANSACTION_CODE = '02';
    const INSTALLMENT_TRANSACTION_CODE = '03';
    const ADD_NEW_CARD_CODE = '04';
    const KEEP_A_CARD_CODE = '05';
    const VERIFY_CARD_FROM_EMV_TOKEN = '06';

    const THREEDS_AUTH_INDICATOR = [
        self::RECURRING_TRANSACTION_CODE,
        self::INSTALLMENT_TRANSACTION_CODE,
    ];
}
