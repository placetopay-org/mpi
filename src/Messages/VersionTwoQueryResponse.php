<?php

namespace PlacetoPay\MPI\Messages;

class VersionTwoQueryResponse extends MPIBaseMessage
{

    private $id;
    private $authenticationValue;
    private $validSignature;
    private $eci;
    private $acsTransId;
    private $dsTransID;
    private $threeDSServerTransId;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->authenticationValue = $data['authenticationValue'];
        $this->validSignature = $data['validSignature'];
        $this->eci = $data['eci'];
        $this->acsTransId = $data['acsTransId'];
        $this->dsTransID = $data['dsTransID'];
        $this->threeDSServerTransId = $data['threeDSServerTransId'];
    }

    public static function loadFromResult($result, $id = null)
    {
        $data = [
            'id' => $id,
            'authenticationValue' => $result['transStatus'],
            'validSignature' => $result['authenticationValue'],
            'eci' => $result['eci'],
            'acsTransId' => $result['acsTransID'],
            'dsTransID' => $result['dsTransID'],
            'threeDSServerTransId' => $result['threeDSServerTransID'],
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

    public function authenticated()
    {
        return $this->authenticationValue;
    }

    public function eci()
    {
        return $this->eci;
    }
}