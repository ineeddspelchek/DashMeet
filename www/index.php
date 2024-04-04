<?php
// By Sean Katauskas, Henry Newton
// https://cs4640.cs.virginia.edu/han5jn/
       
// Licenses for UI Elements
// https://github.com/microsoft/vscode-codicons/blob/main/LICENSE 
// https://github.com/Templarian/MaterialDesign/blob/master/LICENSE 

// DEBUGGING ONLY! Show all errors.
error_reporting(E_ALL);
ini_set("display_errors", 1);

spl_autoload_register(function ($classname) {
        include "/students/han5jn/students/han5jn/DashMeet/$classname.php";
});

$controller = new controller($_GET);

$controller->run();
