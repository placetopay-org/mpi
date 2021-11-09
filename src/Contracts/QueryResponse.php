<?php

namespace PlacetoPay\MPI\Contracts;

use PlacetoPay\MPI\Messages\MPIBaseMessage;

abstract class QueryResponse extends MPIBaseMessage
{
    abstract public function id();

    abstract public function isAuthenticated(): bool;

    abstract public function authenticated(): ?string;

    abstract public function eci(): string;

    abstract public function cavv(): ?string;

    abstract public function xid(): ?string;

    abstract public function version(): string;

    abstract public function extra(): array;
}
