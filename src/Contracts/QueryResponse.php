<?php

namespace PlacetoPay\MPI\Contracts;

use PlacetoPay\MPI\Messages\MPIBaseMessage;

abstract class QueryResponse extends MPIBaseMessage
{
    abstract function id();

    abstract function isAuthenticated(): bool;

    abstract function authenticated(): string;

    abstract function eci(): string;

    abstract function cavv(): ?string;

    abstract function xid(): ?string;

}