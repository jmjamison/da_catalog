<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"

"http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<title>Data Archive Catalog: Search Results</title>


<link href="../_css/2col_leftNav.css" rel="stylesheet" type="text/css">

<body> 


<h1 id="siteName"><a href="index.php"><img src="../_images/logo75.jpg" width="75" height=""></a> Social Science Data Archive</h1> 

<div id="container">


<?php  
	include("../_includes/SSDA_menubar.php");  
//
// SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
//
?>
    
<div id="content" style="padding: 30px 50px 30px 50px;"> 
<?php
	

	 //error_reporting(E_ALL);
//ini_set('display_errors', 1);

	$currentHTTP = "http://data-archive.library.ucla.edu/da_catalog/";	
	//SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
	include("../_includes/SSDA_librarydatabase.php");  //SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
	// class for database connections
	include "../_classes/class.Database.php";
	
// Define configuration
// define info pulled from SSDA_librarydatabase.php
define("DB_HOST", $db_host);
define("DB_PORT", $db_port);
define("DB_USER", $db_username);
define("DB_PASS", $db_password);
define("DB_NAME", $db_name);
	
// should be adding "class.Database.php";	
//function __autoload($class_name) {
	// echo 'class.' . $class_name . '.php<br>';
	//include 'class.' . $class_name . '.php';
//}

$searchTerm =  $_GET['searchTerm']; 
	
	
	// check, if NOT set 
	if (!isset($_GET['searchTerm'])) { 
		echo "<span style='margin-left: 0; text-align: center; background-color: powderblue;'>
		<a href='da_catalog_titles.php'>No citations selected. Return to catalog.</a></span><br>";
		die ("No citations selected.");
		
		}
		
	

	
$query_searchStuff = "select distinct StudyNum, Title from (SELECT title.Title, title.Restricted, title.StudyNum, pifull.pi, shfull.subject, shfull.icpsrcountry FROM (((title LEFT JOIN picode ON title.tisort = picode.tisort) LEFT JOIN pifull ON picode.picode = pifull.picode) LEFT JOIN shcode ON title.tisort = shcode.tisort) LEFT JOIN shfull ON shcode.subjectcode = shfull.subjectcode) as searchStuff where ((Title like '%" . $searchTerm . "%') or (StudyNum like '%" . $searchTerm . "%') or (pi like '%" . $searchTerm . "%') or (subject like '%" . $searchTerm . "%') or (icpsrcountry like '%" . $searchTerm . "%'))and (Restricted <> '*')  order by Title";
	
		
	// class.Database.php  is the class to make PDO connections
// initialize new db connection instance
$db = new Database();	 
	
// prepare query
$db->prepareQuery($query_searchStuff);   	
// execute query
$result = $db->executeQuery();	 
	
//$result = $db->resultset();  // execute the query
if (!$result) { 
		die ("Could not query the database: <br />"); 		
		}  // else {  echo "Successfully queried the database.<br>";   }  // for debugging
	
	echo "<H1>Results of Search in Data Archives Studies</H1><br>";
	echo "Search term(s): <strong>" .  $searchTerm . "</strong><br>";
	
	echo "<ul>";
	$row_index = 0;

		while ($row = $db->getRow())  {
		
			$title = $row[ "Title" ];
			$studynum = $row[ "StudyNum" ];
			echo "<li class='alphaTitleList'><A HREF= '" . $currentHTTP . "da_catalog_titleRecord.php?studynumber=$studynum&title=$title'>$title</a></li>";
			
			$row_index++;
			
		}
	echo "</ul>";
	if ($row_index < 1) {
			
			echo "<li class='alphaTitleList'>No items found for search term: $searchTerm</li>";
			
	}
	
	// _destructor class closes connection
	// close the connection		
	//PDO_connection = null;
	
	?>
    
</div>  <!--end content -->
</div>  <!--end container -->
</body>

</html>


