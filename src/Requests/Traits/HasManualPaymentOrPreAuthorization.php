<?php

namespace PlacetoPay\MPI\Requests\Traits;

trait HasManualPaymentOrPreAuthorization
{
    protected static function isPaymentRequestForAgentPayment(array $data): bool
    {
        return isset($data['agentPaymentTransaction'])
            && isset($data['franchise'])
            && empty($data['messageCategory'])
            && array_key_exists($data['franchise'], self::getBrand());
    }

    protected static function isPaymentUnknownOrUndefinedFinalAmount(array $data): bool
    {
        return isset($data['preAuthorization'])
            && isset($data['franchise'])
            && empty($data['messageCategory'])
            && array_key_exists($data['franchise'], self::getBrand());
    }
}
