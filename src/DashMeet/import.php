<?php
    // Sources:
    // https://stackoverflow.com/questions/4064444/returning-json-from-a-php-script

    $importStr = file_get_contents($_FILES["import"]["tmp_name"], true);
    $explodedStr = explode("BEGIN:VEVENT", $importStr);

    $outJSON = open($explodedStr);
    
    for ($i=1; $i < count($explodedStr); $i++) { 
        $outJSON .= addEvent($explodedStr[$i]);
    }

    $outJSON .= "}";


    header('Content-Type: application/json; charset=utf-8');
    echo $outJSON;

    function open($explodedStr) {
        $element0 = $explodedStr[0];
        $out = "{";
        $out .= "\"name\": \" .  . \"";
        return $out;
    }

    function addEvent($inp) {

    }
?>