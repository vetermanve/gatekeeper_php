<?php

namespace App\Landing\Controller;

use Verse\Di\Env;
use Verse\Run\Controller\SimpleController;

class Landing extends SimpleController
{
    public function index ()
    {
        return "Hi there, this is an API resource!";
    }
}