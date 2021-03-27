<!--Reference: Validations taken from The Lecture notes -->
<?php

function alphabetic($value) {
	$reg = "/^[A-Za-z]+$/";
	return preg_match($reg,$value);
}

function alphaNumeric($value) {
	$reg= "/^[A-Za-z0-9]+$/";
	return preg_match($reg,$value);
}

function alphaNumericSpace($value) {
	$reg = "/^[A-Za-z0-9 ]+$/";
	return preg_match($reg,$value);
}

function sqlMetaChars($value) {
	$reg = "/((\%3D)|(=))[^\n]*((\%27)|(\')|(\-\-)|(\%3B)|(;))/i";
	return preg_match($reg,$value);
}

function sqlInjection($value) {
	$reg = "/\w*((\%27)|(\'))((\%6F)|o|(\%4F))((\%72)|r|(\%52))/i";
	return preg_match($reg,$value);
}

function sqlInjectionInsert($value) {
	$reg = "/((\%27)|(\'));\s*insert/i";
	return preg_match($reg,$value);
}

function sqlInjectionUpdate($value) {
	$reg = "/((\%27)|(\'));\s*update/i";
	return preg_match($reg,$value);
}

function crossSiteScripting($value) {
	$reg = "/((\%3C)|<)((\%2F)|\/)*[a-z0-9\%]+((\%3E)|>)/i";
	return preg_match($reg,$value);
}

function crossSiteScriptingImg($value) {
	$reg = "/((\%3C)|<)((\%69)|i|(\%49))((\%6D)|m|(\%4D))((\%67)|g|(\%47))[^\n]+((\%3E)|>)/i";
	return preg_match($reg,$value);
}

function IntegerNum($value) {
	$reg = "/(^-?\d\d*$)/";
	if($value<0){
		return false;
	}
	return preg_match($reg,$value);
}

function validateDate($start, $end){
    $formatDate='Y-m-d h-i-s';
    $start = date($formatDate,strtotime($start));
    $end = date($formatDate,strtotime($end));
    $current = date("Y-m-d h-i-s");
    if($start > $current && $end > $current){
        if($end > $start){
            return 1;
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}
?>