<?php
include 'connection.php';

if (file_exists($site_path.'src/functions/common_functions.php')) {
    require_once $site_path.'src/functions/common_functions.php';
} else {
    echo "File not found!";exit;
}
if(isset($_GET['pg']) && $_GET['pg'] == "routes"){
    include 'src/default_routes.php';
} else if(isset($_GET['pg']) && $_GET['pg'] != ""){
    include 'src/templates/'.$_GET['pg'].".php";
} else {
    include 'src/templates/index.php';  
}
?>