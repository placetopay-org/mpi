<?php

namespace PlacetoPay\MPI\Requests\Fields;

use PlacetoPay\MPI\Contracts\MPIException;
use PlacetoPay\MPI\Requests\Traits\HasManualPaymentOrPreAuthorization;

class ThreeRIIndForFranchise
{
    use HasManualPaymentOrPreAuthorization;

    private const BRAND = [
        'mastercard' => [
            '3RI_PAYMENT_REQUEST_IS_FOR_AGENT_PAYMENT' => '85',
            '3RI_UNKNOWN_OR_UNDEFINED_FINAL_AMOUNT' => '86',
        ],
    ];

    public static function build(array $data): void
    {
        if (self::isPaymentRequestForAgentPayment($data)
            && self::validateIndicator($data, '3RI_PAYMENT_REQUEST_IS_FOR_AGENT_PAYMENT')
        ) {
            throw new MPIException('The value of the threeRIInd field for an 3RI Agent Payment transaction must be 85 for mastercard.');
        }

        if (self::isPaymentUnknownOrUndefinedFinalAmount($data)
            && self::validateIndicator($data, '3RI_UNKNOWN_OR_UNDEFINED_FINAL_AMOUNT')
        ) {
            throw new MPIException('The value of the threeRIInd field for 3RI payment request is for an unknown and undefined final amount prior to the purchase transaction must be 86 for mastercard.');
        }
    }

    private static function validateIndicator(array $data, string $type): bool
    {
        return isset($data['threeRIInd'])
            && $data['threeRIInd'] !== self::BRAND[$data['franchise']][$type];
    }

    protected static function getBrand(): array
    {
        return self::BRAND;
    }
}
