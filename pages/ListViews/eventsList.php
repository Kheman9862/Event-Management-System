<?php
require("../../includes/headerAdmin.php");
echo HeaderNavbar::navbar();
require_once ("../../controller/dbController.php"); 
session_name("kheman");

session_start();
if(!isset($_SESSION['name']))
header("Locaion: ./login.php");
$db = new DB();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event List</title>
    <link rel="stylesheet" href="../../css/stylesextra.css">

</head>
<body class="back">
    <?php
        echo $db->getAllEventsAsTable();
    ?>
</body>
</html>