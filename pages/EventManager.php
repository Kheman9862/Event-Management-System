<?php
require("../includes/headerAdmin2.php");
echo HeaderNavbar2::navbar2();
session_name("kheman");
session_start();
    require "../controller/dbController.php";
    $db = new DB();
 ?>

 
<!DOCTYPE html>
<html lang="en">
    <head>
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css/bootstrap-extended.min.css">
<link rel="stylesheet" type="text/css" href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/fonts/simple-line-icons/style.min.css">
<link rel="stylesheet" type="text/css" href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css/colors.min.css">
<link rel="stylesheet" type="text/css" href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/stylesextra.css">
<title>Home</title>
</head>
<body class="back">

<!-- 
•	Add/View/Edit/Delete events
•	Add/View/Edit/Delete sessions
•	Plus all attendee functionality -->


<div class="grey-bg container-fluid">
  <section id="minimal-statistics">
    <div class="row">
      <div class="col-12 mt-3 mb-1">
        <h4 class="text-uppercase">Event Manager Dashboard</h4>
        <p>Manage the cards below.</p>
      </div>
    </div>
    <div class="row">
    <div class="col-xl-6 col-sm-12 col-12">
        <div class="card">
        <a href="./ListViews/eventsListEventManager.php" class="clickbut"></a>
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h3 class="warning"><?php echo count($db->getAllEvents())?></h3>
                  <span>Events</span>
                </div>
                <div class="align-self-center">
                  <i class="icon-fire warning font-large-2 float-right"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-6 col-sm-12 col-12">
        <div class="card">
        <a href="./ListViews/sessionsListSessionManagers.php" class="clickbut"></a>
          <div class="card-content">
            <div class="card-body">
              <div class="media d-flex">
                <div class="media-body text-left">
                  <h3 class="primary"><?php echo count($db->getAllSessions())?></h3>
                  <span>Sessions</span>
                </div>
                <div class="align-self-center">
                  <i class="icon-magic-wand primary font-large-2 float-right"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>
</body>
</html>
