<?php
session_start();
/*
Name	: index.php
Purpose	: A php page that displays the tolkien database from the database server with a variety of functionalities
	  and also gives an option to add a new character. There are special funtions for the admin user.
Author	: Abhimanyu Ambastha; abhimanyu.ambastha@colorado.edu
Version : 1.0
Date	: 03/31/2016
Files   : index.php - this file which is the homepage
	  header.php - this file contains the header html syntax
	  footer.php - this file contains the footer html syntax
	  hw7-lib.php - this library file contains the helper functions for the main index.php file
	  add.php -  this file contains the logic to add a new character 
and a new user
	  login.php - renders the page for a login prompt
	  logout.php - logs out the user
	  adminfunc.php - includes admin functionalities if the user is admin
	  error.php - the error page if something is not right
	  test.php - a file for testing cases. Used for development
*/ 

/* use the header and the library files */
include_once('/var/www/html/hw9/header.php');
include_once('/var/www/html/hw9/hw9-lib.php');
//include_once('/var/www/html/hw9/test-lib.php');

#error_log("This is a test",0);
#error_log("This is a test", 1, "abhip310@gmail.com");

/* check for integers */
icheck($s);
icheck($sid);
icheck($cid);
icheck($bid);

/* connect to the db */
connect($db);

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
		
	/* this case displays the books in the story */
	case 1:
		$sid=mysqli_real_escape_string($db,$sid);
		if($stmt=mysqli_prepare($db,"SELECT bookid,title from books where storyid=?")) {
			mysqli_stmt_bind_param($stmt,"s",$sid);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $bid, $title);
			echo "<table><caption><b>Books</b></caption>";
			while(mysqli_stmt_fetch($stmt)){
				$bid=htmlspecialchars($bid);
				$title=htmlspecialchars($title);
				echo "
				<tr><td><a href=index.php?s=2&bid=$bid>$title</a></td></tr>
				";
			}
			echo "</table>";
			mysqli_stmt_close($stmt);
		}
		break;
		
	/* this case displays the characters in the book */
	case 2:
		$bid=mysqli_real_escape_string($db,$bid);
                if($stmt=mysqli_prepare($db,"select characters.characterid,name from characters,appears where bookid=? and characters.characterid=appears.characterid")) {
                        mysqli_stmt_bind_param($stmt,"s",$bid);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt, $cid, $name);
			echo "<table><caption><b>Characters</b></caption>";
                        while(mysqli_stmt_fetch($stmt)){
                                $cid=htmlspecialchars($cid);
                                $name=htmlspecialchars($name);
                                echo "
                                <tr><td><a href=index.php?s=3&cid=$cid>$name</a></td></tr>
                                ";
                        }
			echo "</table>";
                        mysqli_stmt_close($stmt);
                }
                break;
	
	/* this case displays books and stories that a particular character has appeared in */
	case 3:
		$cid=mysqli_real_escape_string($db,$cid);
                if($stmt=mysqli_prepare($db,"select name,title,story from books,appears,characters,stories where appears.characterid=? and appears.bookid=books.bookid and books.storyid=stories.storyid and characters.characterid=?")) {
                        mysqli_stmt_bind_param($stmt,"ss",$cid,$cid);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt, $name,$title,$story);
			echo "<table><caption><b>Appearances</b></caption><tr>
                        <th>Character</th>
                        <th>Book</th>
                        <th>Story</th></tr>";
                        while(mysqli_stmt_fetch($stmt)){
				$name=htmlspecialchars($name);
                                $title=htmlspecialchars($title);
				$story=htmlspecialchars($story);
                                echo "
                                <tr><td><a href=index.php>$name</a></td>
				<td><a href=index.php>$title</a></td>
				<td><a href=index.php>$story</a></td>
				</tr>
                                ";
			}
			echo "</table>";
                        
                        mysqli_stmt_close($stmt);
                }
                break;

	/* this case displays the list of all characters along with their images */
	case 10:
               if($stmt=mysqli_prepare($db,"select characters.characterid,name,url from characters,pictures where characters.characterid=pictures.characterid")) {
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt,$cid,$name,$url);
                        echo "<table><caption><b>Character List</b></caption><tr>";
                        while(mysqli_stmt_fetch($stmt)){
				$cid=htmlspecialchars($cid);
                                $name=htmlspecialchars($name);
                                $url=htmlspecialchars($url);
                                echo "
                                <tr><td><a href=index.php?s=3&cid=$cid>$name</a></td>
                                <td><img src=$url></td>
                                </tr>
                                ";
                        }
                        echo "</table>";

                        mysqli_stmt_close($stmt);
                }
                break;
}


/* the footer html */
include_once('/var/www/html/hw9/footer.php');
?>
