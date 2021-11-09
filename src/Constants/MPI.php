<?php

namespace PlacetoPay\MPI\Constants;

interface MPI
{
    public const VERSION_ONE = 'v1';
    public const VERSION_TWO = 'v2';

    public const LOOKUP_ENDPOINTS = [
      self::VERSION_ONE => '/api/lookup',
      self::VERSION_TWO => '/api/threeds/v2/sessions',
    ];

    public const QUERY_ENDPOINTS = [
        self::VERSION_ONE => '/api/transactions/',
        self::VERSION_TWO => '/api/threeds/v2/transactions/',
    ];

    public const PAYMENT_TRANSACTION_CODE = '01';
    public const RECURRING_TRANSACTION_CODE = '02';
    public const INSTALLMENT_TRANSACTION_CODE = '03';
    public const ADD_NEW_CARD_CODE = '04';
    public const KEEP_A_CARD_CODE = '05';
    public const VERIFY_CARD_FROM_EMV_TOKEN = '06';

    public const THREEDS_AUTH_INDICATOR = [
        self::RECURRING_TRANSACTION_CODE,
        self::INSTALLMENT_TRANSACTION_CODE,
    ];
}
