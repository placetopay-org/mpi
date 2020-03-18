<?php

namespace PlacetoPay\MPI\Entities;

use PlacetoPay\MPI\Constants\MPI;
use PlacetoPay\MPI\Messages\VersionOneLookUpResponse;

class DirectorFactory
{
    public static function create(string $version)
    {
        if ($version === MPI::VERSION_ONE){
            return new VersionOneDirector() ;
        }

        if ($version === MPI::VERSION_TWO){
            return new VersionTwoDirector() ;
        }

        throw new \Exception('Wrong version provided for lookup methods');
    }
}
