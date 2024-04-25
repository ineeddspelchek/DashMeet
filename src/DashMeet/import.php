<?php
    // Sources:
    // https://stackoverflow.com/questions/4064444/returning-json-from-a-php-script
    // https://icalendar.org/
    // https://stackoverflow.com/questions/4286423/remove-first-4-characters-of-a-string-with-php
    // https://www.geeksforgeeks.org/what-is-stdclass-in-php/
    // https://stackoverflow.com/questions/10542310/how-can-i-get-the-last-7-characters-of-a-php-string
    
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


    $calRes = $this->db->query("insert into calendars (name, userID, json) values ($1, $2, $3) returning *;",
                        $JSONArr["name"], $userID, $outJSON);

    foreach ($JSONArr["events"] as $key => $event) {
        $event = (array) $event;
        if(isset($event["repeats"]) && $event["repeats"] == "WEEKLY") {
            for ($i=0; $i < 500; $i++) {
                if(strlen($event["repeatsUntil"]) != 2) {
                    $continue = true;

                    if($event["repeatsOn"] == "") {
                        $continue = "t" == $this->db->query("select (timestamp '" . $event["start"] .  "' + '" . $i . " weeks') <= '" . $event["repeatsUntil"] . "';")[0]["?column?"]; // TODO SUSCEPTIBLE TO INJECTION
                    
                        if($continue) {
                            $res = $this->db->query("insert into events (calendarID, name, start, stop) values ($1, $2, timestamp '" . $event["start"] .  "' + '" . $i . " weeks', timestamp '" . $event["end"] .  "' + '" . $i . " weeks');",
                                intval($calRes[0]["id"]), $event["name"]);
                        }
                        else {
                            $continue = false;
                            break;
                        }
                    }
                    else {
                        $dow = $this->db->query("select extract(dow from timestamp'" . $event["start"] . "');")[0]["extract"];

                        $offsets = getDayOffsets($event["repeatsOn"], $dow);

                        foreach ($offsets as $key => $offset) {
                            //var_dump($event["name"], $event["start"], $event["repeatsOn"], $offsets);
                            $continue = "t" == $this->db->query("select (timestamp '" . $event["start"] .  "' + '" . $i . " weeks' + '" . $offset . "days') <= '" . $event["repeatsUntil"] . "';")[0]["?column?"]; // TODO SUSCEPTIBLE TO INJECTION
                            
                            if($continue) {
                                $res = $this->db->query("insert into events (calendarID, name, start, stop) values ($1, $2, timestamp '" . $event["start"] .  "' + '" . $i . " weeks' + '" . $offset . "days', timestamp '" . $event["end"] .  "' + '" . $i . " weeks' + '" . $offset . "days');",
                                    intval($calRes[0]["id"]), $event["name"]);
                            }
                            else {
                                $continue = false;
                                break;
                            }
                        }
                    }
                }
            }
        }
        else {
            $res = $this->db->query("insert into events (calendarID, name, start, stop) values ($1, $2, $3, $4);",
                                intval($calRes[0]["id"]), $event["name"], $event["start"], $event["end"]);
        }
    }

    header("Location: ?command=account");
    return;

    function getDayOffsets($str, $dow) {
        $offsets = [];
        $map = ["SU", "MO", "TU", "WE", "TH", "FR", "SA"];

        $exploded = explode(",", $str);
        for ($i=0; $i < count($exploded); $i++) {
            array_push($offsets, array_search($exploded[$i], $map)-$dow);
        }
        return $offsets;
    }

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
        if(preg_match("/\nDTSTART.*\n/", $inp, $testReg)) {
            if(!str_contains(substr($testReg[0], -10), "T")) {
                return "";
            }
        }

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