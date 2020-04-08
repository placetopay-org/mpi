<?php

namespace PlacetoPay\MPI\Contracts;

use PlacetoPay\MPI\Messages\MPIBaseMessage;

abstract class LookupResponse extends MPIBaseMessage
{
    abstract function processUrl(): string;

    abstract function canAuthenticate(): bool;

    abstract function identifier(): string;

}