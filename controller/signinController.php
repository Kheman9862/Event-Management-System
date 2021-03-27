<?php 
session_name("kheman");
session_start();
require_once ("../controller/dbController.php");
require_once ("../controller/sanitization.php");
require_once ("../controller/validations.php");
$db = new DB();


$passwordHashed ="";
$msg="";
$err=false;

$name = isset($_POST['name'])?sanitizeInput($_POST['name']):'';
$password = isset($_POST['name'])?sanitizeInput($_POST['password']):'';

if(empty($name)|| !alphaNumeric($name) || strlen($name) > 30 || $name == "Enter a name"){
    header("Location:../pages/login.php?error=validname");
}

if(empty($password)){
    header("Location:../pages/login.php?error=nopassword");
}



else{
    $passwordHashed=hash('sha256',$password);
    $user= $db->loginUser($name,$passwordHashed);
    $_SESSION['name'] = $user->getName();
    $_SESSION['idattendee'] = $user->getIdattendee();
    $_SESSION['role'] = $user->getRole();
    header("Location:../pages/homepage.php");
    exit;
}
?>

