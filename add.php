<?php
session_start();
session_regenerate_id();

/* use the header and the library files */
#include_once('/var/www/html/hw6/header.php');
include_once('/var/www/html/hw9/hw9-lib.php');
//include_once('/var/www/html/hw9/test-lib.php');

/* check for integers */
icheck($s);
icheck($sid);
icheck($cid);
icheck($bid);



/* connect to the db */
connect($db);

/*checks if logged in */
if(!isset($_SESSION['authenticated'])){
        authenticate($db,$post_user,$post_pwd);
}
checkAuth();

#echo "request".$_SERVER['REQUEST_METHOD']."<br>";
#$query="https://100.66.1.53";
#echo substr($_SERVER["HTTP_REFERER"],0,strlen($query));
#var_dump($_SESSION);

include_once('/var/www/html/hw9/header.php');

/*echo "$post_user, $post_pwd, $post_email";
echo $_SESSION['userid'];
echo $_SESSION['authenticated'];
echo $_SESSION['email'];
echo $_SESSION['ip'];
echo "<br>";
echo $_SESSION['password'];
echo "<br>";
echo $_SESSION['salt'];
*/

	
/* determine what to do based on value of s */
switch($s){
	case 0; /* case 0 does nothing */
	
	/* the default case is to display the stories */
	default:
		if($stmt=mysqli_prepare($db,"SELECT storyid, story from stories")) {
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $sid, $story);
			echo "<table><caption><b>List of stories</b></caption>";
			while(mysqli_stmt_fetch($stmt)){
				echo "<tr>
				<td><a href=index.php?s=1&sid=$sid>$story</a></td></tr>
				";
			}
			echo "</table>";
			mysqli_stmt_close($stmt);
		}
		break;




	/* Displays the form to add a character */
	case 11:
		echo "
		<form method=post action=add.php> 
		<table><caption><b>Add Character to Books</b></caption>
		<tr><td>Character Name</td><td><input type=text name=characterName value=''></td></tr>
		<tr><td>Race</td><td><input type=text name=characterRace value=''></td></tr>
		<tr><td>Side</td><td><input type=radio name=characterSide value=good>Good<input type=radio name=characterSide value=evil>Evil</td></tr>
		<tr><td><input type=hidden name=s value=12></td><td><input type=submit name=submit value=submit></td></tr>
		</table> 
		</form>
		";
		break;

	/* After adding a character, this case adds a url for its picture */
	case 12:
		$characterName=mysqli_real_escape_string($db,$characterName);
		$characterRace=mysqli_real_escape_string($db,$characterRace);
		$characterSide=mysqli_real_escape_string($db,$characterSide);
		
		if($stmt=mysqli_prepare($db,"insert into characters set name=?,race=?,side=?")){
			mysqli_stmt_bind_param($stmt,"sss",$characterName,$characterRace,$characterSide);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}		
		
		if($stmt=mysqli_prepare($db,"select characterid,name from characters where name=? order by characterid desc limit 1")){
			mysqli_stmt_bind_param($stmt,"s",$characterName);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt,$cid,$name);
			while(mysqli_stmt_fetch($stmt)){
				$cid=$cid;
				$name=$name;
			}
			mysqli_stmt_close($stmt);
		}
		else{
			echo "Error with Query";
		}
		echo "
		<form method=post action=add.php?s=13&cid=$cid>
		<table><caption><b>Add picture for character $name</b></caption>
		<tr><td>Picture URL</td><td><input type=text name=characterPicture value=''></td></tr>
		<tr><td><input type=hidden name=characterName value=$name></td><td><input type=submit name=submit value=submit></td></tr>
		</table>
		</form>";
		break;

	/* After adding the picture, this page gives an option to add the character to books */
	case 13:
		$characterPicture=mysqli_real_escape_string($db,$characterPicture);
		$cid=mysqli_real_escape_string($db,$cid);
		$characterName=mysqli_real_escape_string($db,$characterName);
		if($stmt=mysqli_prepare($db,"insert into pictures set url=?,characterid=?")){
                	mysqli_stmt_bind_param($stmt,"ss",$characterPicture,$cid);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                }
		echo "<form>Added picture for $characterName<br>
		<a href=add.php?s=14&cid=$cid>Add character to books</a>
		</form>
		";
		break;
               
	/* Here the user can select the books in which the character should be added */ 
	case 14:
		$cid=mysqli_real_escape_string($db,$cid);
		if($stmt=mysqli_prepare($db,"select distinct(a.bookid),b.title from books b,appears a where a.bookid not in (select bookid from appears where characterid=?) and b.bookid=a.bookid")){
			mysqli_stmt_bind_param($stmt,"s",$cid);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt,$bid,$title);
			echo "<form method=post action=add.php?s=15&cid=$cid>Add to books<br>
			Select book
			<select name=bid>
			";
			while(mysqli_stmt_fetch($stmt)){
				echo "
				<option value=$bid>$title</option>
				";
                        }
			echo "
			<input type=submit name=submit value=\"Add to book\">
			</form>
			";
                        mysqli_stmt_close($stmt);


		}
		
		break;

	/* this case works along with the case 14 to add character to the books until done */
	case 15:
		$cid=mysqli_real_escape_string($db,$cid);
		$bid=mysqli_real_escape_string($db,$bid);
		
		if($stmt=mysqli_prepare($db,"insert into appears set bookid=?,characterid=?")){
                        mysqli_stmt_bind_param($stmt,"ss",$bid,$cid);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                }
		
		if($stmt=mysqli_prepare($db,"select distinct(a.bookid),b.title from books b,appears a where a.bookid not in (select bookid from appears where characterid=?) and b.bookid=a.bookid")){
                        mysqli_stmt_bind_param($stmt,"s",$cid);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt,$bid,$title);
                        echo "<form method=post action=add.php?s=15&cid=$cid>Add to books<br>
                        Select book
                        <select name=bid>
                        ";
                        while(mysqli_stmt_fetch($stmt)){
                                echo "
                                <option value=$bid>$title</option>
                                ";
                        }
                        echo "
                        <input type=submit name=submit value=\"Add to book\">
                        </form>
			<a href=index.php?s=3&cid=$cid>Done</a>
                        ";
                        mysqli_stmt_close($stmt);


                }
		break;

/*Renders the form for the admin to add a user and then adds the user to DB*/
	case 90:
		
		if(isset($_SESSION['admin'])){
			if($_SESSION['admin']){

		
				echo "
				<form method=post action=add.php> 
				<table><caption><b>Add Users</b></caption>
				<tr><td>Username</td><td><input type=text name=new_user value=''></td></tr>
				<tr><td>Password</td><td><input type=password name=new_pwd value=''></td></tr>
				<tr><td>Email</td><td><input type=text name=new_email value=''></td></tr>
				<tr><td><input type=hidden name=s value=91></td><td><input type=submit name=submit value=submit></td></tr>
				</table> 
				</form>
				";
			}
		
			else{
				header("Location: /hw9/error.php");
				exit;
			}
		}
	break;

/*adds the user to db and prints confirmation*/
	case 91:
		$new_user=mysqli_real_escape_string($db,$new_user);
		$new_pwd=mysqli_real_escape_string($db,$new_pwd);
		$new_email=mysqli_real_escape_string($db,$new_email);
		$new_salt=bin2hex(openssl_random_pseudo_bytes(32));
		$epass=hash('sha256',$new_pwd.$new_salt);
		if($stmt=mysqli_prepare($db,"insert into users set username=?,email=?,password=?,salt=?")){
			mysqli_stmt_bind_param($stmt,"ssss",$new_user,$new_email,$epass,$new_salt);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}
		echo "Added new user $new_user";
	
	break;

/*lists the users in the db*/
	case 95:

		if(isset($_SESSION['admin'])){
			if($_SESSION['admin']){


				if($stmt=mysqli_prepare($db,"SELECT username,email from users")){
					mysqli_stmt_execute($stmt);
					mysqli_stmt_bind_result($stmt, $username, $email);
					echo "<table><caption><b>List of users</b></caption>";
					echo "<tr><th>Username</th><th>Email</th></tr>";
					while(mysqli_stmt_fetch($stmt)){
						echo "<tr>
						<td>$username</td>
						<td>$email</td>
						</tr>
						";
					}
					echo "<table>";
				}
			}
		
			else{
				header("Location: /hw9/error.php");
				exit;
			}
		}


			
		break;

/*Renders a form to select the user whose password has to be updated*/
	case 96:
		if(isset($_SESSION['admin'])){
			if($_SESSION['admin']){



				if($stmt=mysqli_prepare($db,"SELECT userid,username from users")){
					mysqli_stmt_execute($stmt);
					mysqli_stmt_bind_result($stmt,$uid,$username);
					echo "<form method=post action=add.php?s=97>Update Password<br>
					Select User
					<select name=uid>
					";
					while(mysqli_stmt_fetch($stmt)){
						echo "
						<option value=$uid>$username</option>
						";
                		        }
					echo "
					<input type=submit name=submit value=\"Update Password\">
					</form>
					";
                        		mysqli_stmt_close($stmt);


				}
			}
		
			else{
				header("Location: /hw9/error.php");
				exit;
			}
		}

		
		break;

/*renders form to enter the new password*/		
	case 97:
		$uid=mysqli_real_escape_string($db,$uid);
		/*echo "<br>uid is....";
		echo $uid;
		echo "<br>this feature is being added";*/
		echo "<form method=post action=add.php?s=98>
		<label>Enter new password</label>
		<input type=password name=update_pwd>
		<input type=hidden name=uid value=$uid>
		<input type=submit name=submit value=\"Update Password\">
		</form>";
		break;

/*Updates the password and prints confirmation. Note that even a new salt
is generated */
	case 98:
		$uid=mysqli_real_escape_string($db,$uid);
		$update_pwd=mysqli_real_escape_string($db,$update_pwd);
		$update_salt=bin2hex(openssl_random_pseudo_bytes(32));
		$update_epass=hash('sha256',$update_pwd.$update_salt);
		/*echo "<br>here goes...";
		echo "<br>$uid";
		echo "<br>$update_pwd";
		echo "<br>$update_salt";
		echo "<br>$update_epass";*/
		
		if($stmt=mysqli_prepare($db,"UPDATE users SET password=?,salt=? where userid=?")){
			mysqli_stmt_bind_param($stmt,"sss",$update_epass,$update_salt,$uid);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}
		echo "Password has been updated";
		
		break;
	

/*List failed login attempts for an admin*/
	case 99:
		
		if(isset($_SESSION['admin'])){
			if($_SESSION['admin']){



				$query="select ip,count(*) as num from login where action='Fail' group by ip order by num desc";
				if($stmt=mysqli_prepare($db,$query)){
					mysqli_stmt_execute($stmt);
					mysqli_stmt_bind_result($stmt,$ip,$failed_attempts);
					echo "
					<table width:40% border=2><caption><b>Failed login attempts</b></caption>
					<tr><th>IP Address</th><th>Failed attempts</th></tr>
					";
					while(mysqli_stmt_fetch($stmt)){
						echo "
						<tr><td  align=center>$ip</td><td align=center>$failed_attempts</td></tr>
						";
                 		       }
					echo "</table>";
					mysqli_stmt_close($stmt);
				}			
			}
		
			else{
				header("Location: /hw9/error.php");
				exit;
			}
		}

	
	break;	
		

}

/* the footer html */
include_once('/var/www/html/hw9/footer.php');
?>
