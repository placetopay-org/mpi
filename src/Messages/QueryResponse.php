<?php


namespace PlacetoPay\MPI\Messages;


class QueryResponse extends MPIBaseMessage
{
    protected $status;
    protected $validSignature;
    protected $eci;
    protected $cavv;
    protected $xid;

    public function __construct($data)
    {
        $this->status = $data['status'];
        $this->validSignature = $data['validSignature'];
        $this->eci = $data['eci'];
        $this->cavv = $data['cavv'];
        $this->xid = $data['xid'];
    }

    /**
     * Returns true if the authentication process has been successfully completed
     * @return bool
     */
    public function isAuthenticated()
    {
        return $this->authenticationStatus() == 'Y' && $this->validSignature();
    }

    /**
     * Returns the indicator for the authentication from the ACS
     *  “Y” - Successful Authentication
     *  “A” - Successful Attempt
     *  “N” - Failed Authentication
     *  “U” - Unable to Authenticate
     * @return string
     */
    public function authenticationStatus()
    {
        return $this->status;
    }

    /**
     * Return true if the signature for the ACS response has been validated
     * @return bool
     */
    public function validSignature()
    {
        return $this->validSignature;
    }

    /**
     * Represents the Electronic Commerce Indicator
     *  For VISA
     *      05 - Issuer Liability
     *      06 - Issuer Liability
     *      07 - Merchant Liability
     * @return string
     */
    public function eci()
    {
        return $this->eci;
    }

    /**
     * Cardholder Authentication Verification Value (CAVV)
     * @return mixed
     */
    public function cavv()
    {
        return $this->cavv;
    }

    /**
     * Identifier of the resulting transaction for the authentication process
     * @return mixed
     */
    public function xid()
    {
        return $this->xid;
    }

    /**
     * @param $result
     * @return QueryResponse
     * @throws \PlacetoPay\MPI\Exceptions\ErrorResultMPI
     */
    public static function loadFromResult($result)
    {
        parent::loadFromResult($result);

        $data = [
            'status' => $result['authentication_status'],
            'validSignature' => $result['validated_signature'],
            'eci' => $result['eci'],
            'cavv' => $result['cavv'],
            'xid' => $result['xid'],
        ];

        return new self($data);
    }

}