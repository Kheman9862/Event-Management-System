<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <link rel="stylesheet" href="../css/stylesextra.css">

    <title>Event Planning</title>
</head>
<body>
<nav class="nav">
        <div class="container">
            <div class="logo">
                <a href="#">Event Planning Management System</a>
            </div>
            <div id="mainListDiv" class="main_list">
                <ul class="navlinks">
                    <li><a href="#">About</a></li>
                    <li><a href="#">Signup Policy</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <span class="navTrigger">
                <i></i>
                <i></i>
                <i></i>
            </span>
        </div>
    </nav>
  
    <section class="home">
    <div class="loginBox">
    <form class="login" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
      <header>Please Sign In</header>
      <div><?php 
        if(isset($_GET["error"])){
            if($_GET["error"]=="nopassword"){
                echo "<h1 class='text-danger'>Please enter a valid password</h1>";
            }
            if($_GET["error"]=="nouser"){
                echo "<h1 class='text-danger'>Please enter a valid username</h1>";
            }
            if($_GET["error"]=="validname"){
                echo "<h1 class='text-danger'>Please enter a name</h1>";
            }
            if($_GET["error"]=="nomatch"){
                echo "<h1 class='text-danger'>User and password do not match</h1>";
            }
        }
      ?></div>
      <div class="field"><span class="fa fa-user fa-2x"></span><input type="text" placeholder="User Name" name="name"></div>
      <div class="field"><span class="fa fa-lock fa-2x"></span><input type="password" placeholder="Password" name="password"></div>
      <button type="submit" class="submit" >Sign in</button>
    </form>
    </div>
    </section>

    <div>
			<h2 class="myH2">About This Website</h2>
				<p class="myP text-center">
				
				This is an event management system website built for admin, users and event managers.
			</p>
    </div>

    <div>
			<h2 class="myH2">Signup Policy</h2>
				<p class="myP text-center">
				
			If you are not logged in you can ask admin to enroll you to this website.
			</p>
    </div>

    <div>
			<h2 class="myH2">Contact</h2>
				<p class="myP text-center">
				
				You can reach to me or know more about me by clicking <a href="https://khemangarg.vercel.app/">here</a>.
			</p>
    </div>

<!-- Jquery needed -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="js/scripts.js"></script>

<!-- Function used to shrink nav bar removing paddings and adding black background -->
    <script>
        $(window).scroll(function() {
            if ($(document).scrollTop() > 50) {
                $('.nav').addClass('affix');
                console.log("OK");
            } else {
                $('.nav').removeClass('affix');
            }
        });

    $('.navTrigger').click(function () {
    $(this).toggleClass('active');
    console.log("Clicked menu");
    $("#mainListDiv").toggleClass("show_list");
    $("#mainListDiv").fadeIn();

});

    </script>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  require_once ('../controller/signinController.php');
} 
?>
</body>
</html>