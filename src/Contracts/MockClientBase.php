<?php

namespace PlacetoPay\MPI\Contracts;

trait MockClientBase
{
    protected static $instance;

    protected function __construct()
    {
    }

    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }
}
