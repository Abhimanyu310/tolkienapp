<?php


isset($_REQUEST['s']) ? $s = strip_tags($_REQUEST['s']) : $s = "";
isset($_REQUEST['sid']) ? $sid = strip_tags($_REQUEST['sid']) : $sid = "";
isset($_REQUEST['bid']) ? $bid = strip_tags($_REQUEST['bid']) : $bid = "";
isset($_REQUEST['cid']) ? $cid = strip_tags($_REQUEST['cid']) : $cid = "";
isset($_REQUEST['characterName']) ? $characterName = strip_tags($_REQUEST['characterName']) : $characterName = "";
isset($_REQUEST['characterRace']) ? $characterRace = strip_tags($_REQUEST['characterRace']) : $characterRace = "";
isset($_REQUEST['characterSide']) ? $characterSide = strip_tags($_REQUEST['characterSide']) : $characterSide = "";
isset($_REQUEST['characterPicture']) ? $characterPicture = strip_tags($_REQUEST['characterPicture']) : $characterPicture = "";
isset($_REQUEST['post_user']) ? $post_user = strip_tags($_REQUEST['post_user']) : $post_user = "";
isset($_REQUEST['post_pwd']) ? $post_pwd = strip_tags($_REQUEST['post_pwd']) : $post_pwd = "";
isset($_REQUEST['post_email']) ? $post_email = strip_tags($_REQUEST['post_email']) : $post_email = "";
isset($_REQUEST['new_user']) ? $new_user = strip_tags($_REQUEST['new_user']) : $new_user = "";
isset($_REQUEST['new_pwd']) ? $new_pwd = strip_tags($_REQUEST['new_pwd']) : $new_pwd = "";
isset($_REQUEST['new_email']) ? $new_email = strip_tags($_REQUEST['new_email']) : $new_email = "";
isset($_REQUEST['uid']) ? $uid = strip_tags($_REQUEST['uid']) : $uid = "";
isset($_REQUEST['update_pwd']) ? $update_pwd = strip_tags($_REQUEST['update_pwd']) : $update_pwd = "";



$white_list=array(
1 => "198.18.0.154" 
);


function check_if_empty($a){
	if(empty($a)){
		error_log("user empty",0);
		header("Location: /hw9/login.php");
		exit;
	}
}

function in_white_list($ip){
	if(in_array($ip, $GLOBALS['white_list'])){
		error_log("white",0);
		return True;
	}
	return False;


}

function time_before($db, $time_interval){ // check arbitrary intervals
	if($stmt = mysqli_prepare($db,"select date_sub(now(), INTERVAL 1 HOUR)")){
		mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt,$time_before);
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
		error_log("time before".$time_before,0);
		return $time_before;
	}
}


function failed_logins($db, $from_time){
	$query="select count(*) as num from login where ip=? and action=? and date>? group by ip";
    if($stmt = mysqli_prepare($db,$query)){
		$action="Fail";
    	mysqli_stmt_bind_param($stmt,"sss",$_SERVER['REMOTE_ADDR'],$action,$from_time);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt,$failed_attempts);
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
		error_log("failed attempts".$failed_attempts,0);
		return $failed_attempts;

	}
}	



function allow_login($userid,$email){
	session_regenerate_id();
	$_SESSION['userid']=$userid;
	$_SESSION['email']=$email;
	$_SESSION['authenticated']="yes";
	$_SESSION['ip']=$_SERVER['REMOTE_ADDR'];
	$_SESSION['HTTP_USER_AGENT']=md5($_SERVER['SERVER_ADDR'].$_SERVER['HTTP_USER_AGENT']);
	$_SESSION['created']=time();
	if($userid==1){
		$_SESSION['admin']=True;
	}
	else{
		$_SESSION['admin']=False;
	}


}



function check_password($db,$user,$pass){
	error_log("checking Pass",0);
	list($db_userid,$db_password,$db_salt,$db_email) = password_user_info($db,$user);
	error_log("db pass".$db_password,0);
	error_log("db salt".$db_salt,0);
	
	$epass=hash('sha256',$pass.$db_salt);
	error_log("epass".$epass,0);
	if($epass == $db_password){
		error_log("pass match",0);
		allow_login($db_userid,$db_email);
		log_to_db($db,"Success",$user);
	}
	else{
		error_log("pass not match",0);
		log_to_db($db,"Fail",$user);
		$msg="***ERROR*** Tolkien App has a failed login attemp from ".$_SERVER['REMOTE_ADDR']." by user ".$user;
		error_log($msg,0);
		login();
		exit;
	}

}

function password_user_info($db,$user){
	$query="select userid,email,password,salt from users where username=?";
	if($stmt = mysqli_prepare($db,$query)){
		mysqli_stmt_bind_param($stmt,"s",$user);
 		mysqli_stmt_execute($stmt);	
  		mysqli_stmt_bind_result($stmt,$userid,$email,$password,$salt);
		while(mysqli_stmt_fetch($stmt)){
			$userid=$userid;
			$password=$password;
			$salt=$salt;
			$email=$email;
		}
		mysqli_stmt_close($stmt);
		return array($userid,$password,$salt,$email);
	}

}

/*authenticate a user and create its session object*/
function authenticate($db,$postUser,$postPass){

	
	# do not proceed if username is blank
	check_if_empty($postUser);

	# check if ip in whitelist
	$allow=in_white_list($_SERVER['REMOTE_ADDR']);

	# if not in whitelist, check for no of failed logins in last hour and block if >5
	if(!$allow){
		error_log("not white",0);
		$last_hour = time_before($db, "1 HOUR");
		error_log("last hr".$last_hour,0);
		$failed_attempts = failed_logins($db,$last_hour);
		if($failed_attempts>5){
			error_log('blocking',0);
			header("Location: /hw9/login.php");
			exit;
		}
	}
	
	check_password($db,$postUser,$postPass);
}




function log_to_db($db,$result,$postUser) { // log the logins to db
	$query="insert into login set ip=?,date=now(),user=?,action=?";
	if($stmt = mysqli_prepare($db,$query)){
		mysqli_stmt_bind_param($stmt,"sss",$_SERVER['REMOTE_ADDR'],$postUser,$result);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	


} 


function connect(&$db){  //connect to the db
    #$mycnf="/etc/project-mysql.conf";
    //$mycnf="/etc/projectssl-mysql.conf";
	$mycnf="/etc/hw9-mysql.conf";
    if(!file_exists($mycnf)){
        echo "Error file not found: $mycnf";
    }

    $mysql_ini_array=parse_ini_file($mycnf);
    $db_host=$mysql_ini_array["host"];
    $db_user=$mysql_ini_array["user"];
    $db_pass=$mysql_ini_array["pass"];
    $db_port=$mysql_ini_array["port"];
    $db_name=$mysql_ini_array["dbName"];
    $db = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);

    if(!$db) {
        print "Error connecting to DB: " . mysqli_connect_error();
        exit;
    }

    /*$db=mysqli_init();
    $db_sslkey='/etc/mysql-ssl/server-key.pem';
    $db_sslcert='/etc/mysql-ssl/server-cert.pem';
    mysqli_ssl_set($db,$db_sslkey,$db_sslcert,NULL,NULL,NULL);
    mysqli_real_connect($db,$db_host,$db_user,$db_pass,$db_name,$db_port);
    if(mysqli_connect_errno()){
        print "DB Error: " . mysqli_connect_error();
        exit;

    }*/
}


/*Check if properly authenticated and no attempt to hack a session is made*/
function checkAuth(){
	/*Check if the user agent and server address is same as used to log in*/
	check_http_user_agent();

	/*Check if the remote address is same*/
	check_remote_address();
	
	/*Check if session timed out after 30 mins*/
	check_timeout();

	/*Check that the request is not from a phishing site*/
	check_request_method();

}


function icheck($i) { //Check for numeric
	if ($i != null) {
		if(!is_numeric($i)) {
			logout();
			//error() and log to error
			print "<b> ERROR: </b> 
			Invalid Syntax.";
			exit;
		}
	}
}


function check_http_user_agent(){
	if(isset($_SESSION['HTTP_USER_AGENT'])){
		if($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['SERVER_ADDR'].$_SERVER['HTTP_USER_AGENT'])){
			logout();
		}
	
	}
	else {
		logout();
	}
}

function check_remote_address(){
	if(isset($_SESSION['ip'])){
		if($_SESSION['ip'] != $_SERVER['REMOTE_ADDR']){
			logout();
		}
	}
	else {
		logout();
	}
}

function check_timeout(){
	if(isset($_SESSION['created'])){
		if(time() - $_SESSION['created'] > 1800) {
			logout();
		}
	}
	else {
		logout();
	}
}

function check_request_method(){
	if(isset($_SERVER["REQUEST_METHOD"])){
		if(isset($_SERVER["HTTP_REFERER"])){
			$origin = "https://100.66.1.53";
			if(substr($_SERVER["HTTP_REFERER"],0,strlen($origin)) != $origin){
				logout();
			}
		}
		else {
			logout();
		}
	}
	else {
		logout();
	}
}





function error() { // logs out the user
	header("Location: /hw9/error.php");	
	exit;
}

function login() {
	header("Location: /hw9/login.php");
}

function logout() { // logs out the user
	header("Location: /hw9/logout.php");	
}


?>
