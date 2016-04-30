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


/*authenticate a user and create its session object*/
function authenticate($db,$postUser,$postPass){

	# do not proceed if username is blank
	if(empty($postUser)){
	header("Location: /hw9/login.php");
	exit;
	}
	
	$white_list=array(
	1 => "198.18.0.154" 
	);
	$allow=False;
	/*foreach($white_list as $key=>$ip){
		if($ip == $_SERVER['REMOTE_ADDR']){
		$allow=True;
		break;
		}
	}*/
	# check if ip in whitelist
	if(in_array($_SERVER['REMOTE_ADDR'],$white_list)){
		$allow=True;
	}
	
	# if not in whitelist, check for no of failed logins in last hour and block if >5
	if(!$allow){
		if($stmt = mysqli_prepare($db,"select date_sub(now(), INTERVAL 1 HOUR)")){
			mysqli_stmt_execute($stmt);
	                mysqli_stmt_bind_result($stmt,$last_hour);
			mysqli_stmt_fetch($stmt);
			echo "last hour";
			#echo $last_hour;
			#$last_hour=$last_hour;
			}
			mysqli_stmt_close($stmt);

		$action="Fail";
		$query="select count(*) as num from login where ip=? and action=? and date>? group by ip";
        	if($stmt = mysqli_prepare($db,$query)){
                	mysqli_stmt_bind_param($stmt,"sss",$_SERVER['REMOTE_ADDR'],$action,$last_hour);
                	mysqli_stmt_execute($stmt);
                	mysqli_stmt_bind_result($stmt,$failed_attempts);
                	while(mysqli_stmt_fetch($stmt)){
                       		$failed_attempts=$failed_attempts;
			}
			mysqli_stmt_close($stmt);
			
			#echo "Here";
			#echo $failed_attempts;
		
		}
		if($failed_attempts>5){
			header("Location: /hw9/login.php");
			exit;
		}
	
	}

	$query="select userid,email,password,salt from users where username=?";
	if($stmt = mysqli_prepare($db,$query)){
		mysqli_stmt_bind_param($stmt,"s",$postUser);
 		mysqli_stmt_execute($stmt);	
  		mysqli_stmt_bind_result($stmt,$userid,$email,$password,$salt);
		while(mysqli_stmt_fetch($stmt)){
			$userid=$userid;
			$password=$password;
			$salt=$salt;
			$email=$email;
		}
		mysqli_stmt_close($stmt);
		$epass=hash('sha256',$postPass.$salt);
		if($epass == $password){
			session_regenerate_id();
#			$_SESSION['salt']=$salt;
#			$_SESSION['password']=$password;
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
			
			$result="Success";
			log_to_db($db,$result,$postUser);
              		
		}	
 		else{
		#	echo "Failed to Login";
			$result="Fail";
			log_to_db($db,$result,$postUser);
			$msg="***ERROR*** Tolkien App has a failed login attemp from ".$_SERVER['REMOTE_ADDR']." by user ".$postUser;
			error_log($msg,0);
			header("location: /hw9/login.php");
			exit;			
		}
		
	}
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
	$mycnf="/etc/hw9-mysql.conf";
	#$mycnf="/etc/hw7ssl-mysql.conf";
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


function icheck($i) { //Check for numeric
	if ($i != null) {
		if(!is_numeric($i)) {
			print "<b> ERROR: </b> 
			Invalid Syntax.";
			exit;
		}
	}
}

/*Check if properly authenticated and no attempt to hack a session is made*/
function checkAuth(){
	/*Check if the user agent and server address is same as used to log in*/
	if(isset($_SESSION['HTTP_USER_AGENT'])){
		if($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['SERVER_ADDR'].$_SERVER['HTTP_USER_AGENT'])){
		#	error_log("User agent ok",3,"/var/log/test.log");
#			error_log("10",0);
			logout();
		}
	
	}
	else {
#		error_log("11",0);
		logout();
	}

	/*Check if the remote address is same*/
	if(isset($_SESSION['ip'])){
		if($_SESSION['ip'] != $_SERVER['REMOTE_ADDR']){
	#		error_log("session ip ok",3,"/home/abhi/test.log");
#			error_log("20",0);
			logout();
		}
	}
	else {
#		error_log("21",0);
		logout();
	}
	
	/*Check if session timed out after 30 mins*/
	if(isset($_SESSION['created'])){
		if(time() - $_SESSION['created'] > 1800) {
	#		error_log("not timed out",3,"/home/abhi/test.log");
#			error_log("30",0);
			logout();
		}
	}
	else {
#		error_log("31",0);
		logout();
	}

	/*Check that the request is not from a phishing site*/
	if(isset($_SERVER["REQUEST_METHOD"])){
		if(isset($_SERVER["HTTP_REFERER"])){
			$origin = "https://100.66.1.53";
			if(substr($_SERVER["HTTP_REFERER"],0,strlen($origin)) != $origin){
#				error_log("HTTP origin ok",3,"/var/log/test.log");
#				error_log("40",0);
				logout();
			}
		}
		else {
#			error_log("41",0);
			logout();
		}
	}
	else {
#		error_log("42",0);
		logout();
	}

}


function logout() { // logs out the user
	header("Location: /hw9/logout.php");	
}

?>
