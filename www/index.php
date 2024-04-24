<?php
// By Sean Katauskas, Henry Newton
// https://cs4640.cs.virginia.edu/han5jn/
       
// Licenses for UI Elements
// https://github.com/microsoft/vscode-codicons/blob/main/LICENSE 
// https://github.com/Templarian/MaterialDesign/blob/master/LICENSE 

// SOURCES:
// https://hasura.io/blog/top-psql-commands-and-flags-you-need-to-know-postgresql
// https://stackoverflow.com/questions/5648509/browsers-wont-read-updated-css
// https://stackoverflow.com/questions/1374753/passing-base64-encoded-strings-in-url

// DEBUGGING ONLY! Show all errors. TODO: REMOVE
error_reporting(E_ALL);
ini_set("display_errors", 1);

spl_autoload_register(function ($classname) {
        include "/opt/src/DashMeet/$classname.php";
});

$controller = new controller($_GET);

$controller->run();
