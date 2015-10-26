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
 
 //	error_reporting(E_ALL);
//ini_set('display_errors', 1);

	$currentHTTP = "http://data-archive.library.ucla.edu/da_catalog/";	
	include("SSDA_librarydatabase.php"); 

// sql query statement
	$query = "select distinct ucase(left(archive_db.title.Title,1)) as index_letter, count(*) as index_letter_count from archive_db.title where ucase(left(archive_db.title.Title,1)) regexp '^[A-Za-z]' and archive_db.title.Restricted != '*' group by index_letter";
	// echo "<br>$query<br>";
	

	// PDO connect  
	//$PDO_string = "mysql:host=".$db_host.";dbname=da_catalog";
	$PDO_string = "mysql:host=" . $db_host . ";port=" . $db_port . ";dbname=" . $db_name;	
	//echo "$PDO_string<br>";
	
	
	// this is where it seems to quit -----
	$PDO_connection = new PDO($PDO_string, $db_username, $db_password);
	//echo "$PDO_connection<br>";
	
	
	 
	 
	 // PDO - create prepared statement
	 $PDO_query = $PDO_connection->prepare($query);
	 // PDO - execute the query
	 $result = $PDO_query->execute();
	 
	 if (!$result) {
		die ("Could not query the database: <br />". mysql_error());
	}
	
	 
		echo "<table id='alphaList' align='center'> ";
		echo "<tr>";  // start a row
		
		$itemCount = 1;	  // count off the number of items in the alpha-block, 5 letters across
		
		while ($row = $PDO_query->fetch(PDO::FETCH_ASSOC))  {
		// Non-PDO code ---------------------
		//while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			
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
	
	// close the connection
	// mysql_close($connection);		
	$PDO_connection = null;
	
		
	?>
</div>  <!--end content -->
</div>  <!--end container -->
</body>

</html>


