<?php

namespace PlacetoPay\MPI\Messages;

use PlacetoPay\MPI\Constants\MPI;
use PlacetoPay\MPI\Contracts\QueryResponse;

class QueryResponseVersionTwo extends QueryResponse
{
    private $id;
    private $transStatus;
    private $eci;
    private $acsTransId;
    private $dsTransID;
    private $threeDSServerTransId;
    private $authenticationValue;
    private $transStatusReason;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->transStatus = $data['transStatus'];
        $this->eci = $data['eci'];
        $this->acsTransId = $data['acsTransId'] ?? null;
        $this->dsTransID = $data['dsTransID' ?? null];
        $this->threeDSServerTransId = $data['threeDSServerTransId'] ?? null;
        $this->authenticationValue = $data['authenticationValue'] ?? null;
        $this->transStatusReason = $data['transStatusReason'] ?? null;
    }

    public static function loadFromResult($result, $id = null)
    {
        parent::loadFromResult($result);

        $data = [
            'id' => $id,
            'transStatus' => $result['transStatus'],
            'eci' => $result['eci'],
            'acsTransId' => $result['acsTransID'] ?? null,
            'dsTransID' => $result['dsTransID'] ?? null,
            'threeDSServerTransId' => $result['threeDSServerTransID'] ?? null,
            'authenticationValue' => $result['authenticationValue'] ?? null,
            'transStatusReason' => $result['transStatusReason'] ?? null,
        ];

        return new self($data);
    }

    public function toArray()
    {
        return [
            'id' => $this->id(),
            'enrolled' => 'Y',
            'authenticated' => $this->authenticated(),
            'validSignature' => true,
            'eci' => $this->eci(),
            'xid' => $this->xid(),
            'cavv' => $this->cavv(),
            'threeDSVersion' => $this->version(),
            'extra' => [
                'transStatusReason' => $this->reasonCode(),
                'acsTransId' => $this->acsTransId(),
                'threeDSServerTransID' => $this->threeDsServerTransId(),
            ],
        ];
    }

    public function id()
    {
        return $this->id;
    }

    /**
     * Returns true if the authentication process has been successfully completed.
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return $this->authenticated() == 'Y' && $this->cavv();
    }

    /**
     * Returns the indicator for the authentication from the ACS
     *  “Y” - Successful Authentication
     *  “A” - Successful Attempt
     *  “N” - Failed Authentication
     *  “U” - Unable to Authenticate
     *  ”C” - Challenge Required
     *  ”D” - Challenge Required
     *  ”R” - Failed Authenticate
     *  ”I” - Challenge Required only informative.
     * @return string
     */
    public function authenticated(): string
    {
        return $this->transStatus;
    }

    /**
     * Represents the Electronic Commerce Indicator
     *  For VISA
     *      05 - Issuer Liability
     *      06 - Issuer/Merchant Liability
     *      07 - Merchant Liability.
     * @return string
     */
    public function eci(): string
    {
        return $this->eci;
    }

    /**
     * Return true if the signature for the ACS response has been validated.
     * @return bool
     */
    public function cavv(): ?string
    {
        return $this->authenticationValue;
    }

    public function acsTransId()
    {
        return $this->acsTransId;
    }

    public function xid(): ? string
    {
        return $this->dsTransID;
    }

    public function reasonCode()
    {
        return$this->transStatusReason;
    }

    public function threeDsServerTransId()
    {
        return $this->threeDSServerTransId;
    }

    public function version(): string
    {
        return MPI::VERSION_TWO;
    }

    public function extra(): array
    {
        return [
            'transStatusReason' => $this->reasonCode(),
            'acsTransId' => $this->acsTransId(),
            'threeDSServerTransID' => $this->threeDsServerTransId(),
        ];
    }
}
