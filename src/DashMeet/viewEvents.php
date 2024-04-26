<?php // If ajax requested, fulfill the request and then leave before anything else gets put in the response
    if(isset($_POST["AjaxRequest"])) {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-Encoding");
        header("Access-Control-Max-Age: 1000");
        header("Access-Control-Allow-Methods: GET, OPTIONS");

        header('Content-Type: application/json; charset=utf-8');
        $res = $this->db->query("select json from calendars where id=$1",
                                intval($_POST["id"]));
        echo json_encode($res);
        return;
    }
?>

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
    <link rel="stylesheet/less" type="text/css" href="styles/main.less?ts=\<\?=filemtime('style.css')?"/>
    <script src="less.js" type="text/javascript"></script>
    <script src="jquery.js" type="text/javascript"></script>

    <script>
        function load() {
            console.log("AAAHH")
            $.ajax({
                method: "POST",
                data: { "AjaxRequest": true, "id": <?=$id?> }
            }).done((x) => {
                x = JSON.parse(x[0]["json"])["events"];
                x.forEach(element => {
                    $("div").append("<p>" + element["name"] + "</p>")
                });
            }); 
        }

    </script>
</head>
<body onload="load();">
    <?php include("header.php"); ?>

    <div style="padding-left:10px; padding-top:10px">
        <form action="?command=account" method="post" style="margin-top:10px; margin-bottom:10px">
            <a href="?command=account" class="btn btn-danger">Back</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>    
</body>
</html>

