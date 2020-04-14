<?php

namespace PlacetoPay\MPI\Requests;

use PlacetoPay\MPI\Constants\MPI;
use PlacetoPay\MPI\Contracts\MPIException;
use PlacetoPay\MPI\Contracts\Request;

class LookupRequestVersionTwo implements Request
{
    /**
     * @var string
     */
    private $accNumber;

    /**
     * @var string
     */
    private $cardExpiryDate;

    /**
     * @var string
     */
    private $purchaseAmount;

    /**
     * @var string
     */
    private $purchaseCurrency;

    /**
     * @var string
     */
    private $redirectURI;

    /**
     * @var string
     */
    private $reference;

    /**
     * @var string
     */
    private $threeDSAuthenticationInd;

    /**
     * @var string|null
     */
    private $purchaseInstallData;

    /**
     * @var string|null
     */
    private $recurringFrequency;

    /**
     * @var string|null
     */
    private $recurringExpiry;

    public function __construct($data)
    {
        $this->accNumber = $data['card']['number'];
        $this->cardExpiryDate = $this->expirationYearShort($data['card']['expirationYear']) . $data['card']['expirationMonth'];
        $this->purchaseAmount = $data['amount'];
        $this->purchaseCurrency = $data['currency'];
        $this->redirectURI = $data['redirectUrl'];
        $this->reference = $data['reference'] ?? null;
        $this->threeDSAuthenticationInd = $data['threeDSAuthenticationInd'] ?? null;

        $this->threeDSAuthValidation($data);

        $this->purchaseInstallData = $data['purchaseInstalData'] ?? null;
        $this->recurringFrequency = $data['recurringFrequency'] ?? null;
        $this->recurringExpiry = $data['recurringExpiry'] ?? null;
    }

    /**
     * Returns the expiration year always with YY format.
     * @param string $expirationYear
     * @return string
     */
    public function expirationYearShort(String $expirationYear)
    {
        if (!$expirationYear) {
            return null;
        }
        if (mb_strlen($expirationYear) == 4) {
            $expirationYear = substr($expirationYear, 2, 2);
        }
        return $expirationYear;
    }

    public function toArray(): array
    {
        return [
            'acctNumber' => $this->accNumber,
            'cardExpiryDate' => $this->cardExpiryDate,
            'purchaseAmount' => $this->purchaseAmount,
            'purchaseCurrency' => $this->purchaseCurrency,
            'redirectURI' => $this->redirectURI,
            'threeDSAuthenticationInd' => $this->threeDSAuthenticationInd,
            'reference' => $this->reference,
        ];
    }

    public function endpoint(): string
    {
        return MPI::LOOKUP_ENDPOINTS[MPI::VERSION_TWO];
    }

    /**
     * @param $data
     * @throws MPIException
     */
    protected function threeDSAuthValidation($data)
    {
        if (in_array($this->threeDSAuthenticationInd, MPI::THREEDS_AUTH_INDICATOR)) {
            if (!isset($data['recurringFrequency'])) {
                throw new MPIException("The recurring frequency field is required when three d s authentication ind is {$this->threeDSAuthenticationInd}.");
            }

            if (!isset($data['recurringExpiry'])) {
                throw new MPIException("The recurring expiry field is required when three d s authentication ind is {$this->threeDSAuthenticationInd}.");
            }

            if ($this->threeDSAuthenticationInd == '03' && !isset($data['recurringFrequency'])) {
                throw new MPIException("The purchase instal data field is required when three d s authentication ind is {$this->threeDSAuthenticationInd}.");
            }
        }
    }
}
