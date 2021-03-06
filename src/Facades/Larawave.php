<?php

namespace Kirko\Larawave\Facades;

use Illuminate\Support\Facades\Facade;

class Larawave extends Facade
{
    /**
     * Get the registered name of the component.
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'larawave';
    }
}