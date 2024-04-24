<?php
    // Sources:
    // https://stackoverflow.com/questions/4064444/returning-json-from-a-php-script
    // https://icalendar.org/
    
    // By Henry Newton

    $importStr = file_get_contents($_FILES["import"]["tmp_name"], true);
    $explodedStr = explode("BEGIN:VEVENT", $importStr);

    $outJSON = start($explodedStr);
    for ($i=1; $i < count($explodedStr); $i++) { 
        $outJSON .= addEvent($explodedStr[$i]);
    }
    $outJSON = rtrim($outJSON, ","); #remove comma added by last event
    $outJSON .= close();

    $JSONArr = (array) json_decode($outJSON); 

    //header('Content-Type: application/json; charset=utf-8');
    //echo $outJSON;
    $res = $this->db->query("insert into calendars (name, userID, json) values ($1, $2, $3);",
                        $JSONArr["name"], $userID, $outJSON);
    header("Location: ?command=account");
    return;

    function start($explodedStr) {
        $element0 = $explodedStr[0];
        $out = "{";
        preg_match("/\nX-WR-CALNAME:.*\n/", $element0, $nameReg);
        preg_match("/\nX-WR-TIMEZONE:.*\n/", $element0, $timezoneReg);
        $name = $nameReg[0];
        $timezone = $timezoneReg[0];

        $out .= "\"name\": \"" . addcslashes(trim(explode(":", $name)[1]), "\\") . "\",";
        $out .= "\"timezone\": \"" . addcslashes(trim(explode(":", $timezone)[1]), "\\") . "\",";
        $out .= "\"events\": " . "[";
        return $out;
    }

    function addEvent($inp) {
        $out = "{";

        if(preg_match("/\nSUMMARY:.*\n/", $inp, $nameReg)) {
            $name = $nameReg[0];
            $out .= "\n\"name\": \"" . addcslashes(trim(explode(":", $name)[1]), "\\") . "\",";
        }

        if(preg_match("/\nDTSTART:.*\n/", $inp, $startReg)) {
            $start = $startReg[0];
            $out .= "\"start\": \"" . trim(explode(":", $start)[1]) . "\",";
        }
        elseif(preg_match("/\nDTSTART;.*\n/", $inp, $startReg)) {
            $start = $startReg[0];
            $out .= "\"start\": \"" . trim(explode(":", $start)[1]) . "\",";
        }

        if(preg_match("/\nDTEND:.*\n/", $inp, $endReg)) {
            $end = $endReg[0];
            $out .= "\"end\": \"" . trim(explode(":", $end)[1]) . "\",";
        }
        elseif(preg_match("/\nDTEND;.*\n/", $inp, $endReg)) {
            $end = $endReg[0];
            $out .= "\"end\": \"" . trim(explode(":", $end)[1]) . "\",";
        }

        if(preg_match("/\nRRULE:FREQ=.*\n/", $inp, $repeatsReg)) {
            $repeats = $repeatsReg[0];
            $out .= "\"repeats\": \"" . trim(explode("=", explode(";", $repeats)[0])[1]) . "\",";
            $out .= "\"repeatsUntil\": \"" . trim(explode("=", explode(";", $repeats)[1])[1]) . "\",";
            if(trim(explode("=", explode(";", $repeats)[0])[1]) === "WEEKLY") {
                $out .= "\"repeatsOn\": \"" . trim(explode("=", explode(";", $repeats)[3])[1]) . "\",";
            }
        }

        if(preg_match_all("/\nEXDATE;.*:.*\n/", $inp, $matches)) {
            $out .= "\"excluded\": " . "[\n";
            foreach ($matches[0] as $key => $match) {
                $out .= "\"" . trim(explode(":", $match)[1]) . "\",";
            }
            $out = rtrim($out, ",");
            $out .= "],";
        }

        $out = rtrim($out, ",");
        $out .= "},";
        return $out;
    }

    function close() {
        return "]}";
    }
?>