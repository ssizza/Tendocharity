<?php

namespace App\Http\Controllers;

use Laramin\Utility\Onumoti;

abstract class Controller
{
    public function __construct()
    {
        //
    }

    public static function middleware()
    {
        return [];
    }

}
