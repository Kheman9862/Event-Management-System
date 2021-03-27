<?php
require("../../includes/headerAdmin.php");
echo HeaderNavbar::navbar();
session_name("kheman");
session_start();
if(!isset($_SESSION['name'])){
  header("Locaion: ./login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/font-awesome-4.7.0/css/font-awesome.min.css">
    <title>Add User</title>
    
</head>
<body>
<div class="container mt-5">
    <div class="row">
            <div class="col-3"></div>
            <div class="col-6">
            <?php
      if(isset($_GET["error"])){
        if($_GET["error"]=="invalidbox"){
            echo "<h4 class='text-danger'>Please enter values</h4>";
        }
        if($_GET["error"]=="validname"){
            echo "<h4 class='text-danger'>Please enter a name</h4>";
        }
    }
    ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>"> 
<div class="card">
  <div class="card-header">
  <i class="fa fa-calendar"></i>
    Event
  </div>
  <div class="card-body">
    <h5 class="card-title">Add User</h5>
    <p class="card-text">Please select one role for the user</p>
  <div class="form-group row">
    <label for="name" class="col-sm-2 col-form-label">Username</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="name" value="" name="name" placeholder="Name">
    </div>
  </div>
  <div class="form-group row mt-4">
    <label for="passowrd" class="col-sm-2 col-form-label">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" name="password" id="password" placeholder="Password">
    </div>
  </div>
  <div class="form-group row mt-4">
  <label for="sel1" class="col-sm-2 col-form-label">Select Role:</label>
  <div class="col-sm-10">
  <select class="form-control mt-2" name="role" id="sel1">
  <?php
    require "../../controller/dbController.php";
    $db = new DB();
    $roles= $db->getAllRoles();
        foreach($roles as $r):
           echo '<option value="'.$r->getIdrole().'">'.$r->getName().'</option>';
        endforeach;
  ?>
  </select>
  </div>
</div> 
      <div class="text-center mt-5">
  <button type="submit" class="btn btn-primary">Submit</button> 
  </div>
</div>
</div>
</form>
</div>
<div class="col-3"></div>       
        </div>
    </div>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$name="";
$password ="";
$passwordHashed ="";
$msg="";
$role=$_POST['role'];


require_once ("../../controller/sanitization.php");
require_once ("../../controller/validations.php");



  $name=$_POST['name']?sanitizeInput($_POST['name']):'';
  $password = $_POST['password']?sanitizeInput($_POST['password']):'';
  $role=$_POST['role']?sanitizeInput($_POST['role']):'';

  if(empty($_POST['name'])||empty($_POST['password'])||empty($_POST['role'])){
    header("Location:./addUser.php?error=invalidbox");
    exit();
  }

  if( !alphaNumeric($name) || strlen($name) > 30 || $name == "Enter a name"){
    header("Location:./addUser.php?error=validname");
    exit();
  }

else{
    
    $passwordHashed=hash('sha256',$password);

    $db->insertUser($name,$passwordHashed,$role);

    if($db){
        header("Location: ../ListViews/usersList.php");
    }
    exit;
}  
}
?>
