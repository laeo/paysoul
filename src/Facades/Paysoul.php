<?php

namespace Laeo\Paysoul\Facades;

use Illuminate\Support\Facades\Facade;

class Paysoul extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'paysoul';
    }
}
