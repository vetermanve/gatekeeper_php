<?php


namespace App\Worker\Controller;


use Verse\Run\Controller\SimpleController;

class Sample extends SimpleController
{
    function doCommand() : string {
        return "I'm done!";
    }
}