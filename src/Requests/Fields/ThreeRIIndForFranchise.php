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
        dd(self::isPaymentRequestForAgentPayment($data), self::isPaymentUnknownOrUndefinedFinalAmount($data));
        if (self::isPaymentRequestForAgentPayment($data)
            && isset($data['threeRIInd'])
            && $data['threeRIInd'] !== self::BRAND[$data['franchise']]['3RI_PAYMENT_REQUEST_IS_FOR_AGENT_PAYMENT']
        ) {
            throw new MPIException('The value of the threeDSAuthenticationInd field for an 3RI Agent Payment transaction must be 85 for mastercard.');
        }

        if (self::isPaymentUnknownOrUndefinedFinalAmount($data)
            && isset($data['threeRIInd'])
            && $data['threeRIInd'] !== self::BRAND[$data['franchise']]['3RI_UNKNOWN_OR_UNDEFINED_FINAL_AMOUNT']
        ) {
            throw new MPIException('The value of the threeDSAuthenticationInd field for 3RI payment request is for an unknown and undefined final amount prior to the purchase transaction must be 86 for mastercard.');
        }
    }

    protected static function getBrand(): array
    {
        return self::BRAND;
    }
}