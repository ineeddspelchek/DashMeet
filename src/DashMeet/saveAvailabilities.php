<?php 
// Sources: 
// https://stackoverflow.com/questions/51412196/display-php-errors-in-ajax-request-callback

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-Encoding");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");


try {
    // $host = "db";
    // $port = "5432";
    // $database = "example";
    // $user = "localuser";
    // $password = "cs4640LocalUser!"; 

    // $dbHandle = pgp_connect("host=$host port=$port dbname=$database user=$user password=$password");
    if($_POST["host"] == "true") {
        echo var_dump(pg_query($dbHandle, "select * from meetings;"));
        $res1 = pg_get_result($dbHandle);
        echo pg_result_error($res1);
        $res = pg_query_params($dbHandle, "update meetings set hostJSON=$2 where meetingID=$1;", 
                                [$_POST["meetingID"], $_POST["availabilities"]]);
    }
    else {
        $res = pg_query_params($dbHandle, "update membersOf set json=$3 where meetingID=$1 and memberID=$2;", 
                                [intval($_POST["meetingID"]), intval($_POST["meetingID"]), $_POST["availabilities"]]);
    }
}
catch (\Error $e) {
    echo $e->getMessage();
}
?>
