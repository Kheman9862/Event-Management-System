<?php
ob_start();
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
    <title>Edit Event</title>
    
</head>
<body>
<div class="container mt-5">
    <div class="row">
            <div class="col-3"></div>
            <div class="col-6">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>"> 
        <div class="card">
  <div class="card-header">
  <i class="fa fa-birthday-cake"></i>
    Event
  </div>
  <div class="card-body">
    <h5 class="card-title">Edit Event</h5>
    <p class="card-text">Please edit the event to the table</p>
    <div class="form-group row">
    <label for="name" class="col-sm-2 col-form-label">Event ID</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control" id="name" name="idevent" value= <?php echo $_GET['id']?> placeholder= <?php echo $_GET['id']?>>
    </div>
  </div>
    <div class="form-group row mt-4">
    <label for="name" class="col-sm-2 col-form-label">Event name</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="name" value="" name="name" placeholder="Event Name">
    </div>
  </div>
  <div class="form-group row mt-4">
  <label for="example-datetime-local-input" class="col-2 col-form-label">Date Start</label>
  <div class="col-10">
    <input class="form-control" type="datetime-local" value="" name="datestart" id="example-datetime-local-input">
  </div>
</div>
<div class="form-group row mt-4">
  <label for="example-datetime-local-input" class="col-2 col-form-label">Date End</label>
  <div class="col-10">
    <input class="form-control" type="datetime-local" value="" name="dateend" id="example-datetime-local-input">
  </div>
</div>
<div class="form-group row mt-4">
    <label for="name" class="col-sm-2 col-form-label">Number Allowed</label>
    <div class="col-sm-10">
      <input type="number" class="form-control" id="name" value="" name="numberallowed" placeholder="Number Allowed">
    </div>
  </div>
 <div class="form-group mt-4">
  <label for="sel1">Select Venue:</label>
  
  <select class="form-control" name="venue" id="sel1">
  <?php
    require "../../controller/dbController.php";
    $db = new DB();
    $venues= $db->getAllVenues();
        foreach($venues as $v){
           echo '<option value="'.$v->getIdvenue().'">'.$v->getName().'</option>';
          }
  ?>
  </select>
</div> 

<div class="form-group mt-4">
  <label for="sel1">Select Manager:</label>
  
  <select class="form-control" name="manager" id="sel1">
  <?php
   if($_SESSION['role']==1){
    $users= $db->getAllUsers();
        foreach($users as $u){
           echo '<option value="'.$u->getIdattendee().'">'.$u->getName().'</option>';
}
}
  else if($_SESSION['role']==2){
          $users= $db->getAllUsers();
        foreach($users as $u){
          if($u->getIdattendee()==$_SESSION['idattendee']){
            echo '<option value="'.$u->getIdattendee().'">'.$u->getName().'</option>';
          }
          };
} 
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
    $name=$_POST['name'] ;
    $datestart=$_POST['datestart'] ;
    $dateend=$_POST['dateend'] ;
    $numberallowed = $_POST['numberallowed'] ;
    $venue= $_POST['venue'] ;
    $manager= $_POST['manager'] ;
    $idevent= $_POST['idevent'] ;
    $db->updateEvent($name,$datestart,$dateend,$numberallowed,$venue,$manager,$idevent);
    
    if($_SESSION['role']==1){
      header("Location: ../ListViews/eventsList.php");
  }
  else if($_SESSION['role']==2){
    header("Location: ../ListViews/eventsListEventManager.php");
  }
 

  }  
?>