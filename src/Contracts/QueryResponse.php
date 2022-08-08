<?php

namespace PlacetoPay\MPI\Contracts;

use PlacetoPay\MPI\Constants\MPI;
use PlacetoPay\MPI\Messages\MPIBaseMessage;

abstract class QueryResponse extends MPIBaseMessage
{
    abstract public function id();

    abstract public function isAuthenticated(): bool;

    abstract public function enrolled(): ?string;

    abstract public function authenticated(): ?string;

    abstract public function eci(): string;

    abstract public function cavv(): ?string;

    abstract public function xid(): ?string;

    abstract public function version(): string;

    abstract public function extra(): array;

    public static function readVersion(string $version): string
    {
        if (substr($version, 0, 1) === '1') {
            return MPI::VERSION_ONE;
        }

        return MPI::VERSION_TWO;
    }
}
