<?php 
 
// Sources: 
// https://stackoverflow.com/questions/4036857/how-can-i-remove-a-style-added-with-css-function 

?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Sean Katauskas">
    <meta name="description" content="">  
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"  integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"  crossorigin="anonymous">       
    <script src="jquery.js" type="text/javascript"></script>
    
    <script>
        function load() {
            $(".btn").on("mouseenter", function() {
                $(this).css("transform", "skewY(10deg)");
            });
            $(".btn").on("mouseleave", function() {
                $(this).css("transform", "");
            });
        }

        function save() {
        }
    </script>

</head>

<body onload="load();" onunload="save();">

<div class="container" style="margin-top: 15px;">
            <div class="row">
                <div class="col-xs-12">
                <h1>Register</h1>
                </div>
            </div>
            <?=$message?>
            <div class="row">
                <div class="col-xs-12">
                <form action="?command=createAccount" method="post">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" required>
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <button type="submit" id="register" class="btn btn-primary">Register</button>
                    <a href="?command=welcome" class="btn btn-danger">Back</a>
                </form>
                </div>
            </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
