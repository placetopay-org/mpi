<?php

namespace PlacetoPay\MPI\Entities;

use PlacetoPay\MPI\Constants\MPI;

class MpiManager
{
    public static function create(string $version): MpiContract
    {
        if ($version === MPI::VERSION_ONE){
            return new VersionOneMpi() ;
        }

        if ($version === MPI::VERSION_TWO){
            return new VersionTwoMpi() ;
        }

        throw new \Exception('Wrong version provided for lookup methods');
    }
}
