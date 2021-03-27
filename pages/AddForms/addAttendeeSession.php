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
    <title>Add Attendee To Session</title>
  
</head>
<body>
<div class="container mt-5">
    <div class="row">
            <div class="col-3"></div>
            <div class="col-6">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>"> 
<div class="card">
  <div class="card-header">
  <i class="fa fa-user"></i>
    Attendee Session
  </div>
  <div class="card-body">
    <h5 class="card-title">Add Attendee to Session</h5>
    <p class="card-text">Make sure you are enrolled in event first in order to display your name in session.</p>
    <div class="form-group row">
    <label for="name" class="col-sm-2 col-form-label">Session ID:</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control" id="name" name="session" value= <?php echo $_GET['id']?> placeholder= <?php echo $_GET['id']?>>
    </div>
  </div>
  <div class="form-group row mt-4">
    <label for="name" class="col-sm-2 col-form-label">Event ID:</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control" id="name" name="event" value= <?php echo $_GET['idevent']?> placeholder= <?php echo $_GET['id']?>>
    </div>
  </div>
 <div class="form-group mt-4">
  <label for="sel1">Select Attendee:</label>
  
  <select class="form-control" name="attendee" id="sel1">
  <?php
    require "../../controller/dbController.php";
    $db = new DB();
    $users= $db->getAttendeebyEventsid($_GET['idevent']);
        foreach($users as $u){
           echo '<option value="'.$u->getAttendee().'">'.$db->getUserById($u->getAttendee())->getName().'</option>';
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
      $session=$_POST['session'];
      $attendee=$_POST['attendee'];
      
      $db->insertAttendeeSession($attendee,$session);
      if($_SESSION['role']==1){
        header("Location: ../ListViews/sessionsList.php");
    }
    else if($_SESSION['role']==2){
      header("Location: ../ListViews/sessionsListSessionManagers.php");
    }
}
?>
