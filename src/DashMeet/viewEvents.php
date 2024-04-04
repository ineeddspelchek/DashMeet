<?php 
//Sources:
    // https://stackoverflow.com/questions/8251426/insert-string-at-specified-position
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="UTF-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Henry Newton">
  <meta name="description" content="">  
  <title></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet/less" type="text/css" href="styles/main.less"/>
    <script src="less.js" type="text/javascript"></script></head>

<body>
    <?php include("header.php"); ?>
    <div style="padding-left:10px; padding-top:10px">
        <form action="?command=account" method="post" style="margin-top:10px; margin-bottom:10px">
            <a href="?command=account" class="btn btn-danger">Back</a>
        </form>
        <?php

        $res = $this->db->query("select json from calendars where id=$1",
                                $id);
        $JSONArr = (array) json_decode($res[0]["json"]);

        $events = $JSONArr["events"];
        foreach ($events as $i => $event) {
            $event = (array) $event; 
            echo "<p>" . $event["name"] . ": " . formatTimes($event["start"]) . "  -  ". formatTimes($event["end"]) . "</p>";
        }

        function formatTimes($inp) {
            $out = $inp;
            if(str_contains($out, "T")) {
                $out = substr_replace($out, ":", 13, 0);
                $out = substr_replace($out, ":", 11, 0);
                $out = substr_replace($out, "/", 6, 0);
                $out = substr_replace($out, "/", 4, 0);
                $out = str_replace("T", " ", $out);
                $out = str_replace("Z", "", $out);
            }  
            else {
                $out = substr_replace($out, "/", 6, 0);
                $out = substr_replace($out, "/", 4, 0);
            }
            return "(" . $out . ")";
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>    
</body>
</html>

