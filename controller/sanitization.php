<?php

//sanitizing the input data
function sanitizeInput($var){
    $var = trim($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return $var;
}

?>