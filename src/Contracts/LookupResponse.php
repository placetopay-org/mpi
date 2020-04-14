<?php

namespace PlacetoPay\MPI\Contracts;

use PlacetoPay\MPI\Messages\MPIBaseMessage;

abstract class LookupResponse extends MPIBaseMessage
{
    abstract public function processUrl(): string;

    abstract public function canAuthenticate(): bool;

    abstract public function identifier(): string;

    abstract public function version(): string;

    abstract public function extra(): array;
}
