<?php
namespace App\Controllers;
use Liman\Toolkit\Shell\Command;

class ServiceController
{
    function isActive(){
        $output = runCommand(sudo() . "systemctl is-active patroni.service");

        if (trim($output) == "active") {
            return respond(true,200);
        } 
        else {
            $this->activate();
            return respond(false,200);
        }
    }

    function activate(){
        runCommand(sudo() . "systemctl restart patroni.service");
    }
}