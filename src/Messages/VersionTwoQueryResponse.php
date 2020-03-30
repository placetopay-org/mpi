<?php

namespace PlacetoPay\MPI\Messages;

class VersionTwoQueryResponse extends MPIBaseMessage
{

    private $id;
    private $transStatus;
    private $eci;
    private $acsTransId;
    private $dsTransID;
    private $threeDSServerTransId;
    private $authenticationValue;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->transStatus = $data['transStatus'];
        $this->eci = $data['eci'];
        $this->acsTransId = $data['acsTransId'];
        $this->dsTransID = $data['dsTransID'];
        $this->threeDSServerTransId = $data['threeDSServerTransId'];
        $this->authenticationValue = $data['authenticationValue'];
    }

    public static function loadFromResult($result, $id = null)
    {
        $data = [
            'id' => $id,
            'transStatus' => $result['transStatus'],
            'eci' => $result['eci'],
            'acsTransId' => $result['acsTransID'],
            'dsTransID' => $result['dsTransID'],
            'threeDSServerTransId' => $result['threeDSServerTransID'],
            'authenticationValue' => $result['authenticationValue'],
        ];

        return new self($data);
    }

    public function toArray()
    {
        return [
            'id' => $this->id(),
            'enrolled' => 'Y',
            'authenticated' => $this->authenticated(),
            'eci' => $this->eci(),
            'acsTransId' => $this->acsTransId(),
        ];
    }

    public function id()
    {
        return $this->id;
    }

    /**
     * Returns true if the authentication process has been successfully completed
     * @return bool
     */
    public function isAuthenticated()
    {
        return $this->authenticated() == 'Y' && $this->authenticationValue();
    }

    /**
     * Returns the indicator for the authentication from the ACS
     *  “Y” - Successful Authentication
     *  “A” - Successful Attempt
     *  “N” - Failed Authentication
     *  “U” - Unable to Authenticate
     * @return string
     */
    public function authenticated()
    {
        return $this->transStatus;
    }

    /**
     * Represents the Electronic Commerce Indicator
     *  For VISA
     *      05 - Issuer Liability
     *      06 - Issuer/Merchant Liability
     *      07 - Merchant Liability
     * @return string
     */
    public function eci()
    {
        return $this->eci;
    }

    /**
     * Return true if the signature for the ACS response has been validated
     * @return bool
     */
    public function authenticationValue()
    {
        return $this->authenticationValue;
    }
}