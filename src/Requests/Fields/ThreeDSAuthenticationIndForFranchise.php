<?php

namespace PlacetoPay\MPI\Requests\Fields;

use PlacetoPay\MPI\Contracts\MPIException;

class ThreeDSAuthenticationIndForFranchise
{
    private const BRAND = [
        'mastercard' => [
            'THE_PAYMENT_REQUEST_IS_FOR_AN_AGENT_PAYMENT' => '85',
            'FOR_UNKNOWN_OR_UNDEFINED_FINAL_AMOUNT_BEFORE_PURCHASE_TRANSACTION' => '86',
        ],
    ];

    public static function build(array $data): void
    {
        if (self::isPaymentRequestForAgentPayment($data)
            && $data['threeDSAuthenticationInd'] !== self::BRAND[$data['franchise']]['THE_PAYMENT_REQUEST_IS_FOR_AN_AGENT_PAYMENT']
        ) {
            throw new MPIException('The value of the threeDSAuthenticationInd field for an Agent Payment transaction must be 85 for mastercard.');
        }

        if (self::isPaymentUnknownOrUndefinedFinalAmount($data)
            && $data['threeDSAuthenticationInd'] !== self::BRAND[$data['franchise']]['FOR_UNKNOWN_OR_UNDEFINED_FINAL_AMOUNT_BEFORE_PURCHASE_TRANSACTION']
        ) {
            throw new MPIException('The value of the threeDSAuthenticationInd field for payment request is for an unknown and undefined final amount prior to the purchase transaction must be 86 for mastercard.');
        }
    }

    protected static function isPaymentRequestForAgentPayment(array $data): bool
    {
        return isset($data['agentPaymentTransaction'])
            && isset($data['franchise'])
            && empty($data['messageCategory'])
            && array_key_exists($data['franchise'], self::BRAND);
    }

    protected static function isPaymentUnknownOrUndefinedFinalAmount(array $data): bool
    {
        return isset($data['preAuthorization'])
            && isset($data['franchise'])
            && empty($data['messageCategory'])
            && array_key_exists($data['franchise'], self::BRAND);
    }
}
