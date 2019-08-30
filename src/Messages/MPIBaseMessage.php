<?php

namespace PlacetoPay\MPI\Messages;

use PlacetoPay\MPI\Exceptions\ErrorResultMPI;

abstract class MPIBaseMessage
{
    /**
     * @param $result
     * @throws ErrorResultMPI
     */
    public static function loadFromResult($result)
    {
        if (isset($result['error_number']) || isset($result['error_description'])) {
            throw new ErrorResultMPI($result['error_description'], $result['error_number']);
        }
        if (isset($result['message'])) {
            throw new ErrorResultMPI($result['message'], 40019);
        }
    }

    public abstract function toArray();
}