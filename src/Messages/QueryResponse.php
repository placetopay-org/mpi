<?php

namespace PlacetoPay\MPI\Messages;

class QueryResponse extends MPIBaseMessage
{
    protected $id;
    protected $authenticated;
    protected $validSignature;
    protected $eci;
    protected $cavv;
    protected $xid;

    public function __construct($data)
    {
        $this->id = $data['id'] ?? null;
        $this->authenticated = $data['authenticated'];
        $this->validSignature = $data['validSignature'];
        $this->eci = $data['eci'];
        $this->cavv = $data['cavv'];
        $this->xid = $data['xid'];
    }

    public function id()
    {
        return $this->id;
    }

    /**
     * Returns true if the authentication process has been successfully completed.
     * @return bool
     */
    public function isAuthenticated()
    {
        return $this->authenticated() == 'Y' && $this->validSignature();
    }

    /**
     * Returns the indicator for the authentication from the ACS
     *  “Y” - Successful Authentication
     *  “A” - Successful Attempt
     *  “N” - Failed Authentication
     *  “U” - Unable to Authenticate.
     * @return string
     */
    public function authenticated()
    {
        return $this->authenticated;
    }

    /**
     * Return true if the signature for the ACS response has been validated.
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
     *      06 - Issuer/Merchant Liability
     *      07 - Merchant Liability.
     * @return string
     */
    public function eci()
    {
        return $this->eci;
    }

    /**
     * Cardholder Authentication Verification Value (CAVV).
     * @return mixed
     */
    public function cavv()
    {
        return $this->cavv;
    }

    /**
     * Identifier of the resulting transaction for the authentication process.
     * @return mixed
     */
    public function xid()
    {
        return $this->xid;
    }

    /**
     * Returns this information as array to store it.
     */
    public function toArray()
    {
        return [
            'id' => $this->id(),
            'enrolled' => 'Y',
            'authenticated' => $this->authenticated(),
            'validSignature' => $this->validSignature,
            'eci' => $this->eci(),
            'cavv' => $this->cavv(),
            'xid' => $this->xid(),
        ];
    }

    /**
     * @param $result
     * @param null $id
     * @return QueryResponse
     * @throws \PlacetoPay\MPI\Exceptions\ErrorResultMPI
     */
    public static function loadFromResult($result, $id = null)
    {
        parent::loadFromResult($result);

        $data = [
            'id' => $id,
            'authenticated' => $result['authentication_status'],
            'validSignature' => (bool)$result['validated_signature'],
            'eci' => $result['eci'],
            'cavv' => isset($result['cavv']) ? $result['cavv'] : null,
            'xid' => isset($result['xid']) ? $result['xid'] : null,
        ];

        return new self($data);
    }
}
