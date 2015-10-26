<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"

"http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<title>Data Archive Catalog: Title List</title>


<link href="../_css/2col_leftNav.css" rel="stylesheet" type="text/css">

<body> 


<h1 id="siteName"><a href="index.php"><img src="../_images/logo75.jpg" width="75" height=""></a> Social Science Data Archive</h1> 

<div id="container">


<?php  include("../SSDA_menubar.php");  
//
// SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
//
?>


<br>
<div id="content" style="padding: 30px 50px 30px 50px;">  
<H1 align="center">Titles That Begin With The Letter...</H1><br>

 <?php
 
 error_reporting(E_ALL);
ini_set('display_errors', 1);

	$currentHTTP = "http://data-archive.library.ucla.edu/da_catalog/";	
	include("SSDA_librarydatabase.php");
// Define configuration
// define info pulled from SSDA_librarydatabase.php
define("DB_HOST", $db_host);
define("DB_PORT", $db_port);
define("DB_USER", $db_username);
define("DB_PASS", $db_password);
define("DB_NAME", $db_name);
	
	//include "class.Database.php";
	//include "class.testClass.php";
	
function __autoload($class_name) {
	// echo 'class.' . $class_name . '.php<br>';
	include 'class.' . $class_name . '.php';
}
	
// sql query statement
$query = "select distinct ucase(left(archive_db.title.Title,1)) as index_letter, count(*) as index_letter_count from archive_db.title where ucase(left(archive_db.title.Title,1)) regexp '^[A-Za-z]' and archive_db.title.Restricted != '*' group by index_letter";

// echo "<br>$query<br>";

// class.Database.php  is the class to make PDO connections
// initialize new db connection instance
$db = new Database();	 
	
// prepare query
$db->prepareQuery($query);   	
// execute query
$result = $db->executeQuery();	 
	
//$result = $db->resultset();  // execute the query
if (!$result) { 
		die ("Could not query the database: <br />"); 		
		}  // else {  echo "Successfully queried the database.<br>";   }  // for debugging


		echo "<table id='alphaList' align='center'> ";
		echo "<tr>";  // start a row
		
		$itemCount = 1;	  // count off the number of items in the alpha-block, 5 letters across
		
		
		while ($row = $db->getRow())  {
			
			$index_letter = $row[ "index_letter" ];
			$index_letter_count = $row["index_letter_count"];
			echo "<td><A HREF= '" . $currentHTTP . "da_catalog_titlesThatBeginWith.php?index_letter=$index_letter'>$index_letter</a>&nbsp;($index_letter_count&nbsp;titles)</td>";
			
			if ($itemCount < 5) {			
			
			$itemCount++;
			}
			
			else { 
				
				echo "</tr>";       // end the row
				echo "<tr>";		// start a new row
				
				$itemCount = 1;
			} 
		}
	echo "</table>";
	
	// _destructor class closes connection
	// close the connection		
	//$PDO_connection = null;
		
	?>
</div>  <!--end content -->
</div>  <!--end container -->
</body>

</html>