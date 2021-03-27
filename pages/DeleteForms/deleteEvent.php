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
    <title>Delete Event</title>
    
</head>
<body>
<div class="container mt-5">
    <div class="row">
            <div class="col-3"></div>
            <div class="col-6">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>"> 
<div class="card">
  <div class="card-header">
  <i class="fa fa-calendar"></i>
    Event
  </div>
  <div class="card-body">
    <h5 class="card-title">Delete Event</h5>
    <p class="card-text">Are you sure you want to delete this event?</p>
    <div class="form-group row">
    <label for="name" class="col-sm-2 col-form-label">Event ID</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control" id="name" name="idevent" value= <?php echo $_GET['id']?> placeholder= <?php echo $_GET['id']?>>
    </div>
  </div>
      <div class="text-center mt-5">
  <button type="submit" class="btn btn-danger">Delete</button> 
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
    require "../../controller/dbController.php";
    $db = new DB();
$idevent=$_POST['idevent']; 

$db->deleteEvent($idevent);

if($_SESSION['role']==1){
  header("Location: ../ListViews/eventsList.php");
}
else if($_SESSION['role']==2){
header("Location: ../ListViews/eventsListEventManager.php");
}
}
?>