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
    <title>Add Venue</title>
  
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
        if($_GET["error"]=="validcapacity"){
            echo "<h4 class='text-danger'>Please enter a capacity</h4>";
        }
    }
    ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>"> 
<div class="card">
  <div class="card-header">
  <i class="fa fa-map"></i>
    Venue
  </div>
  <div class="card-body">
    <h5 class="card-title">Add Venue</h5>
    <p class="card-text">Please select one name and Capacity for the venue</p>
    <div class="form-group row">
    <label for="name" class="col-sm-2 col-form-label">Name</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="name" value="" name="name" placeholder="VenueName">
    </div>
  </div>
  <div class="form-group row mt-4">
    <label for="name" class="col-sm-2 col-form-label">Capacity</label>
    <div class="col-sm-10">
      <input type="number" class="form-control" id="name" value="" name="capacity" placeholder="Capacity">
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
require "../../controller/dbController.php";
$db = new DB();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  require_once ("../../controller/sanitization.php");
  require_once ("../../controller/validations.php");



    $name=$_POST['name']?sanitizeInput($_POST['name']):'';
    $capacity = $_POST['capacity']?sanitizeInput($_POST['capacity']):'';

    if(empty($name)&&empty($capacity)){
      header("Location:./addVenue.php?error=invalidbox");
      exit();  
      }
  

    if(empty($name)|| !alphaNumeric($name) || strlen($name) > 30 || $name == "Enter a name"){
      header("Location:./addVenue.php?error=validname");
    exit();
    }
  
    if(empty($capacity)|| !IntegerNum($capacity)){
      header("Location:./addVenue.php?error=validcapacity");
      exit();  
    }
  

  $db->insertVenue($name,$capacity);
  header("Location:../ListViews/venuesList.php");  
}
?>
