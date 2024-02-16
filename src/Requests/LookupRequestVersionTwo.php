<?php

namespace PlacetoPay\MPI\Requests;

use PlacetoPay\MPI\Constants\MPI;
use PlacetoPay\MPI\Contracts\MPIException;
use PlacetoPay\MPI\Contracts\Request;
use PlacetoPay\MPI\Requests\Fields\ThreeDSAuthenticationIndForFranchise;
use PlacetoPay\MPI\Requests\Fields\ThreeRIIndForFranchise;

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
     * @var string
     */
    private $messageCategory;

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

    /**
     * Load other information useful to risk management.
     * @var array
     */
    private $additional = [];

    private bool $agentPaymentTransaction = false;
    private ?string $franchise = null;
    private bool $preAuthorization = false;
    private ?string $threeRIInd;

    public function __construct($data)
    {
        if (!empty($data['currency'])) {
            $this->purchaseAmount = $data['amount'];
            $this->purchaseCurrency = $data['currency'];
            $this->threeDSAuthenticationInd = $data['threeDSAuthenticationInd'] ?? MPI::PAYMENT_TRANSACTION_CODE;
        } else {
            $this->messageCategory = '02';
            $this->threeDSAuthenticationInd = $data['threeDSAuthenticationInd'] ?? MPI::ADD_NEW_CARD_CODE;
        }

        $this->accNumber = $data['card']['number'];
        $this->cardExpiryDate = $this->expirationYearShort($data['card']['expirationYear']) . $data['card']['expirationMonth'];
        $this->redirectURI = $data['redirectUrl'];
        $this->reference = $data['reference'] ?? null;
        $this->agentPaymentTransaction = $data['agentPaymentTransaction'] ?? false;
        $this->preAuthorization = $data['preAuthorization'] ?? false;
        $this->franchise = $data['franchise'] ?? null;
        $this->threeRIInd = $data['threeRIInd'] ?? null;

        $this->purchaseInstallData = $data['purchaseInstallData'] ?? null;
        $this->recurringFrequency = $data['recurringFrequency'] ?? null;
        $this->recurringExpiry = $data['recurringExpiry'] ?? null;

        $this->threeDSAuthValidation($data);
        $this->threeRIValidation();

        $this->loadAdditional($data);
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
        return array_filter(array_merge([
            'acctNumber' => $this->accNumber,
            'cardExpiryDate' => $this->cardExpiryDate,
            'purchaseAmount' => $this->purchaseAmount,
            'purchaseCurrency' => $this->purchaseCurrency,
            'redirectURI' => $this->redirectURI,
            'threeDSAuthenticationInd' => $this->threeDSAuthenticationInd,
            'reference' => $this->reference,
            'messageCategory' => $this->messageCategory(),

            'purchaseInstallData' => $this->purchaseInstallData,
            'recurringFrequency' => $this->recurringFrequency,
            'recurringExpiry' => $this->recurringExpiry,
            'threeRIInd' => $this->threeRIInd,
            'agentPaymentTransaction' => $this->agentPaymentTransaction,
            'preAuthorization' => $this->preAuthorization,
            'franchise' => $this->franchise,
        ], $this->additional));
    }

    public function endpoint(): string
    {
        return MPI::LOOKUP_ENDPOINTS[MPI::VERSION_TWO];
    }

    public function messageCategory()
    {
        return $this->messageCategory;
    }

    /**
     * @param $data
     * @throws MPIException
     */
    protected function threeDSAuthValidation($data): void
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

        ThreeDSAuthenticationIndForFranchise::build($this->toArray());
    }

    protected function threeRIValidation(): void
    {
        ThreeRIIndForFranchise::build($this->toArray());
    }

    private function loadAdditional(array $data)
    {
        if ($payer = $data['payer'] ?? []) {
            $this->additional = array_merge($this->additional, [
                'email' => $payer['email'] ?? null,
                'mobilePhone' => $data['mobile'] ?? null,
                'cardholderName' => $payer['name'] ?? null,
            ]);

            if ($address = $payer['address'] ?? []) {
                $this->additional = array_merge($this->additional, [
                    'billAddrCity' => $address['city'] ?? null,
                    'billAddrCountry' => $address['country'] ?? null,
                    'billAddrLine1' => $address['street'] ?? null,
                    'billAddrPostCode' => $address['postalCode'] ?? null,
                    'billAddrState' => $address['state'] ?? null,
                ]);
            }
        }

        $shipping = $data['buyer'] ?? [];
        if ($data['shipping'] ?? []) {
            $shipping = $data['shipping'];
        }

        if ($shipping) {
            if ($address = $shipping['address'] ?? []) {
                $this->additional = array_merge($this->additional, [
                    'shipAddrCity' => $address['city'] ?? null,
                    'shipAddrCountry' => $address['country'] ?? null,
                    'shipAddrLine1' => $address['street'] ?? null,
                    'shipAddrPostCode' => $address['postalCode'] ?? null,
                    'shipAddrState' => $address['state'] ?? null,
                ]);
            }
        }
    }
}
