<?php

namespace PlacetoPay\MPI\Messages;

class UpdateTransactionResponse
{
    protected $id;
    protected $amount;
    protected $payment;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->amount = $data['amount'] ?? null;
        $this->payment = $data['payment'] ?? null;
    }

    public static function loadFromResult(array $result, $id = null): self
    {
        return new self([
            'id' => $id,
            'amount' => $result['amount'] ?? null,
            'payment' => $result['payment'] ?? null,
        ]);
    }
}