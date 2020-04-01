<?php

namespace PlacetoPay\MPI\Messages;

class LookupResponseVersionTwo extends MPIBaseMessage
{

    private $redirectUrl;
    private $transactionId;
    private $sessionToken;

    public function __construct($data)
    {
        $this->sessionToken = $data['sessionToken'];
        $this->redirectUrl = $data['redirectURL'];
        $this->transactionId = $data['transactionID'];
    }

    public static function loadFromResult($result)
    {
        parent::loadFromResult($result);

        $data = [
            'sessionToken' => $result['sessionToken'],
            'redirectURL' => isset($result['redirectURL']) ? $result['redirectURL'] : null,
            'transactionID' => isset($result['transactionID']) ? $result['transactionID'] : null
        ];

        return new self($data);
    }

    public function toArray()
    {
        return [
            'sessionToken' => $this->sessionToken(),
            'redirectUrl' => $this->processUrl(),
            'identifier' => $this->identifier(),
        ];
    }

    /**
     * Return true if the user can be authenticated through the MPI
     * @return bool
     */
    public function canAuthenticate()
    {
        if ($this->redirectUrl) {
            return true;
        }
        return false;
    }

    public function sessionToken()
    {
        return $this->sessionToken;
    }

    public function processUrl()
    {
        return $this->redirectUrl;
    }

    public function identifier()
    {
        return $this->transactionId;
    }
}