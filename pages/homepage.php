<?php
require("../includes/headerAdmin2.php");
echo HeaderNavbar2::navbar2();
session_name("kheman");
session_start();
	if(!isset($_SESSION['name'])){
		header("Locaion: ./login.php");
  }
  if($_SESSION['role']==1){
      header("Location: ./Admin.php");
  }
  else if($_SESSION['role']==2){
    header("Location: ./EventManager.php");
  }
  else if($_SESSION['role']==3){
    header("Location: ./Attendee.php");
  }

    require "../controller/dbController.php";
    $db = new DB();
 ?>
