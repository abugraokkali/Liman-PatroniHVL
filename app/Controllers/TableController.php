<?php
namespace App\Controllers;
use Liman\Toolkit\Shell\Command;

class TableController
{
    function hbaTable(){
        $hbaComm = "/etc/gopatroniyml --gp --path /tmp/patroni.yml";
        $hbaList = shell_exec($hbaComm);
        $hbaList = explode("\n",$hbaList);
        $tmpList = array( );

        foreach($hbaList as $element){
            if(strpos($element, '-') !== false) {
                $element = str_replace("-","",$element);
                $element = trim($element);
                $data[] = [
                    "element" => $element
                ];
            }
        }
        
        return view('table', [
            "value" => $data,
            "title" => ["Hba List"],
            "display" => ["element"]
        ]);
    }

}