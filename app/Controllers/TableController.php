<?php
namespace App\Controllers;
use Liman\Toolkit\Shell\Command;

class TableController
{
    function hbaTable(){
        $hbaComm = "/tmp/gopatroniyml -gp";
        $hbaList = runCommand(sudo() . $hbaComm);
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