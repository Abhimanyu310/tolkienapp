<?php

isset($_REQUEST['update_pwd']) ? $update_pwd = strip_tags($_REQUEST['update_pwd']) : $update_pwd = "";
if(isset($update_pwd)){
	echo "SET";
}
if((empty($update_pwd))){
	echo "True";
}

$whitelist=array(
	"1" => "11111");

echo $whitelist[1];
$a=False;
if($a){
	echo "Last is true";
}

#$a=DATE_SUB(now(),date_interval_create_from_date_string('1 hour'));
#$a=date_interval_from_date_string('1 hour');
#echo $a;

$postUser = "Abhi";
echo "<br>";
$msg="***ERROR*** Tolkien App has a failed login attemp at ".time()." from ".$_SERVER['REMOTE_ADDR']." by user ".$postUser;
echo $msg;

?>
