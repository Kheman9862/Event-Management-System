<?php
ob_start();
require("../../includes/headerAdmin.php");
echo HeaderNavbar::navbar();
session_name("kheman");
session_start();
if(!isset($_SESSION['name'])){
  header("Locaion: ./login.php");
}
if(isset($_GET['id'])){
  $_SESSION['id']=$_GET['id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/font-awesome-4.7.0/css/font-awesome.min.css">
    <title>Add Session</title>

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
        if($_GET["error"]=="validdate"){
            echo "<h4 class='text-danger'>Please enter a valid date</h4>";
        }
        if($_GET["error"]=="validname"){
            echo "<h4 class='text-danger'>Please enter a name</h4>";
        }
        if($_GET["error"]=="validnumber"){
            echo "<h4 class='text-danger'>Please enter a valid number</h4>";
        }
    }
    ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>"> 
<div class="card">
  <div class="card-header">
  <i class="fa fa-angel"></i>
    Session
  </div>
  <div class="card-body">
    <h5 class="card-title">Add Session</h5>
    <p class="card-text">Please add a Session to the table</p>
    <div class="form-group row">
    <label for="name" class="col-sm-2 col-form-label">Session ID</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control" id="name" name="idsession" value= <?php echo $_GET['id']?> placeholder= <?php echo $_GET['id']?>>
    </div>
  </div>
    <div class="form-group row mt-4">
    <label for="name" class="col-sm-2 col-form-label">Session name</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="name" value="" name="name" placeholder="Event Name">
    </div>
  </div>
  <div class="form-group row mt-4">
  <label for="example-datetime-local-input" class="col-2 col-form-label">Date Start</label>
  <div class="col-10">
    <input class="form-control" type="datetime-local" value="" name="startdate" id="example-datetime-local-input">
  </div>
</div>
<div class="form-group row mt-4">
  <label for="example-datetime-local-input" class="col-2 col-form-label">Date End</label>
  <div class="col-10">
    <input class="form-control" type="datetime-local" value="" name="enddate" id="example-datetime-local-input">
  </div>
</div>
<div class="form-group row mt-4">
    <label for="name" class="col-sm-2 col-form-label">Number Allowed</label>
    <div class="col-sm-10">
      <input type="number" class="form-control" id="name" value="" name="numberallowed" placeholder="Number Allowed">
    </div>
  </div>
 <div class="form-group mt-4">
  <label for="sel1">Select Event:</label>
  
  <select class="form-control" name="event" id="sel1">
  <?php
    require "../../controller/dbController.php";
    $db = new DB();
    if($_SESSION['role']==1){
      $events= $db->getAllEvents();
        foreach($events as $e):
           echo '<option value="'.$e->getIdevent().'">'.$e->getName().'</option>';
        endforeach;
  }
    else if($_SESSION['role']==2){
      $events= $db->getManagerEventsByMid($_SESSION['idattendee']);
          foreach($events as $e):
            
            echo '<option value="'.$e->getEvent().'">'.$db->getAllEventsById($e->getEvent())->getName().'</option>';
            endforeach; 
            };

  ?>
  </select>
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
  
  require_once ("../../controller/sanitization.php");
  require_once ("../../controller/validations.php");

  $name=$_POST['name']?sanitizeInput($_POST['name']):'';
  $startdate=$_POST['startdate']?sanitizeInput($_POST['startdate']):'';
  $enddate=$_POST['enddate']?sanitizeInput($_POST['enddate']):'';
  $numberallowed = $_POST['numberallowed']?sanitizeInput($_POST['numberallowed']):'';
  $event= $_POST['event']?sanitizeInput($_POST['event']):'';
  $idsession = $_POST['idsession']?sanitizeInput($_POST['idsession']):'';


  if(empty($name)||empty($numberallowed)||empty($idsession)){
    header("Location:./editSession.php?id=".$_SESSION['id']."&error=invalidbox");
    exit();
  }

  else if(!alphaNumeric($name) || strlen($name) > 30 || $name == "Enter a name"){
    header("Location:./editSession.php?id=".$_SESSION['id']."&error=validname");
    exit();
  }

  else if(!IntegerNum($numberallowed)){
    header("Location:./editSession.php?id=".$_SESSION['id']."&error=validnumber");
    exit();  
  }

  else if(!validateDate($startdate,$enddate)){
    header("Location:./editSession.php?id=".$_SESSION['id']."&error=validdate");
    exit();  
  }

  else{
    $d= $db->updateSession($name,$numberallowed,$event,$startdate,$enddate,$idsession);
    if($_SESSION['role']==1){
      header("Location: ../ListViews/sessionsList.php");
    }
  else if($_SESSION['role']==2){
    header("Location: ../ListViews/sessionsListSessionManagers.php");
  }
} 
}
?>
