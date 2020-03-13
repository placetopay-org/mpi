<?php

namespace PlacetoPay\MPI\Entities;

use PlacetoPay\MPI\Contracts\MPIFactory;

class threedsVersionTwo implements MPIFactory
{
    private $accNumber;
    private $cardExpiryDate;
    private $purchaseAmount;
    private $purchaseCurrency;
    private $redirectURI;
    private $threeDSAuthenticationInd;
    private $reference;

    public function __construct($data)
    {
        $this->accNumber = $data['card']['number'];
        $this->cardExpiryDate = $data['card']['expirationYear'];
        $this->purchaseAmount = $data['card']['amount'];
        $this->purchaseCurrency = $data['card']['currency'];
        $this->redirectURI = $data['card']['redirectUrl'];
        $this->threeDSAuthenticationInd = '01'; //Validate this
        $this->reference = $data['reference'] ?? null;
    }

    public function toArray(): array 
    {
        return [
            'acctNumber' => $this->accNumber,
            'cardExpiryDate' => '',
            'purchaseAmount' => $this->purchaseAmount,
            'purchaseCurrency' => $this->purchaseCurrency,
            'redirectURI' => $this->redirectURI,
            'threeDSAuthenticationInd' => $this->threeDSAuthenticationInd,
            'reference' => $this->reference,
        ];
    }
}