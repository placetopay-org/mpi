<?php

namespace PlacetoPay\MPI\Messages;

use PlacetoPay\MPI\Constants\MPI;
use PlacetoPay\MPI\Contracts\QueryResponse;

class QueryResponseVersionTwo extends QueryResponse
{
    private $id;
    private $transStatus;
    private $eci;
    private $acsTransID;
    private $dsTransID;
    private $threeDSServerTransID;
    private $authenticationValue;
    private $transStatusReason;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->transStatus = $data['transStatus'];
        $this->eci = $data['eci'];
        $this->acsTransID = $data['acsTransID'] ?? null;
        $this->dsTransID = $data['dsTransID' ?? null];
        $this->threeDSServerTransID = $data['threeDSServerTransID'] ?? null;
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
            'acsTransID' => $result['acsTransID'] ?? null,
            'dsTransID' => $result['dsTransID'] ?? null,
            'threeDSServerTransID' => $result['threeDSServerTransID'] ?? null,
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
            'version' => $this->version(),
            'extra' => $this->extra(),
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

    public function acsTransID()
    {
        return $this->acsTransID;
    }

    public function xid(): ? string
    {
        return $this->dsTransID;
    }

    public function reasonCode()
    {
        return$this->transStatusReason;
    }

    public function threeDSServerTransID()
    {
        return $this->threeDSServerTransID;
    }

    public function version(): string
    {
        return MPI::VERSION_TWO;
    }

    public function extra(): array
    {
        return [
            'transStatusReason' => $this->reasonCode(),
            'acsTransId' => $this->acsTransID(),
            'threeDSServerTransID' => $this->threeDSServerTransID(),
        ];
    }
}
