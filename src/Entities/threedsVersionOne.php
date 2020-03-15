<?php

namespace PlacetoPay\MPI\Entities;

use PlacetoPay\MPI\Contracts\MPIFactory;

class threedsVersionOne implements MPIFactory
{
    /**
     * @var string
     */
    private $locale;
    private $pan;
    private $expiration_year;
    private $expiration_month;
    private $amount;
    private $reference;
    private $currency;
    private $redirect_uri;
    /**
     * @var bool
     */
    private $disable_redirect;
    private $installments;

    public function __construct(array $data)
    {
        $this->locale = isset($data['locale']) ? $data['locale'] : 'es';
        $this->pan = $data['card']['number'];
        $this->expiration_year = $data['card']['expirationYear'];
        $this->expiration_month = $data['card']['expirationMonth'];
        $this->amount = $data['amount'];
        $this->reference = $data['reference'] ?? null;
        $this->currency = $data['currency'];
        $this->redirect_uri = $data['redirectUrl'];
        $this->disable_redirect = isset($data['disableRedirect']) ? $data['disableRedirect'] : false;
        $this->installments = isset($data['card']['installments']) ? $data['card']['installments'] : null;
    }

    public function toArray(): array
    {
        $request = [
            'locale' => $this->locale,
            'pan' => $this->pan,
            'expiration_year' => $this->expiration_year,
            'expiration_month' => $this->expiration_month,
            'amount' => $this->amount,
            'reference' => $this->reference,
            'currency' => $this->currency,
            'redirect_uri' => $this->redirect_uri,
            'disable_redirect' => $this->disable_redirect
        ];

        if (isset($this->installments)) {
            $request['installments'] = $this->installments;
        }

        return $request;
    }
}