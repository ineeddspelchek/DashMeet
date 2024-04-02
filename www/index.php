<!-- By Sean Katauskas, Henry Newton -->

<!-- Licenses for UI Elements
https://github.com/microsoft/vscode-codicons/blob/main/LICENSE 
https://github.com/Templarian/MaterialDesign/blob/master/LICENSE  -->

<?php

// DEBUGGING ONLY! Show all errors.
error_reporting(E_ALL);
ini_set("display_errors", 1);

spl_autoload_register(function ($classname) {
        include "/opt/src/DashMeet/$classname.php";
});

$controller = new controller($_GET);

$controller->run();
