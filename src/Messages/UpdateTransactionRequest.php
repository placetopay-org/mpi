<?php

namespace PlacetoPay\MPI\Messages;

class UpdateTransactionRequest
{
    protected $processor;
    protected $authorization;
    protected $provider = 'PlacetoPay';
    protected $base24;
    protected $iso;

    public function __construct(array $data)
    {
        $this->processor = $data['processor'] ?? null;
        $this->authorization = $data['authorization'] ?? null;
        $this->provider = $data['provider'] ?? $this->provider;
        $this->base24 = $data['base24'] ?? null;
        $this->iso = $data['iso'] ?? null;
    }

    public function processor(): ?string
    {
        return $this->processor;
    }

    public function authorization(): ?string
    {
        return $this->authorization;
    }

    public function provider(): ?string
    {
        return $this->provider;
    }

    public function base24(): ?string
    {
        return $this->base24;
    }

    public function iso(): ?string
    {
        return $this->iso;
    }
}
