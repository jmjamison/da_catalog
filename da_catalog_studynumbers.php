<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"

"http://www.w3.org/TR/html4/loose.dtd">

<html>


<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<title>Data Archive Catalog: Search Catalog by Study Number</title>

 -->
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


<br>
<div id="content" style="padding: 30px 50px 30px 50px;">  
<H1 align="center">Study Numbers</H1>

Search by Study Number.  For any questions please contact <a href='mailto:libbie@ucla.edu'>Elizabeth Stephenson.</a> </td></tr></table><br>
<A NAME="IND"></A>

<?php
	
	//error_reporting(E_ALL);
//ini_set('display_errors', 1);

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

$query = "select title.StudyNum, title.Title FROM title where title.Restricted <> '*' ORDER BY title.StudyNum";
		
	// PDO connect  
	//$PDO_string = "mysql:host=".$db_host.";dbname=da_catalog";
	$PDO_string = "mysql:host=" . $db_host . ";port=" . $db_port . ";dbname=" . $db_name ;
	// echo "$PDO_string<br>";
	$PDO_connection = new PDO($PDO_string, $db_username, $db_password);
	
	// sql query statement  

	// NOTE: original query did not exclude the Restricted items
	// $query = "select title.Title, title.StudyNum, shfull.subject, Left(shfull.subject,1) AS firstLetterIndex, count(*) as titlePerSubjectCount FROM (title INNER JOIN shcode ON title.tisort = shcode.tisort) INNER JOIN shfull ON shcode.subjectcode = shfull.subjectcode group by shfull.subject ORDER BY shfull.subject";
	
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
	
	
	echo "<h2  align='center'>";
	
	// the index term list
	$studynumberList=array();
	
	
	echo "<form action='da_catalog_titleRecord.php' method='put' name='studynumber' target='_self'>";
	echo  "<select name='studynumber' class='alphaTitleList'>";
    
	//$row_index = 0;
	//while ($row = $PDO_query->fetch(PDO::FETCH_ASSOC))  {
		// Non-PDO code ---------------------
		
		//$studynumber = $row['StudyNum'];
		//$title = $row["Title"];
		//echo "<option value='" . $studynumber . "'>" . $studynumber . "</option>";
			//echo "<li class='alphaTitleList'><A HREF= '" . $currentHTTP . "da_catalog_titleRecord.php?studynumber=$studynumber&title=$title'>$studynumber</a></li>";
	 	//$row_index++;
	 		//}
		
		$row_index = 0;
		
	while ($row = $db->getRow())  {
		
		$title = $row['Title'];
		
		$tempStudynumber = $row['StudyNum'];
		
		// code to insure that the studynumbers sort correctly
		
		$numeric_only = preg_replace('/[a-zA-Z]/', '', $tempStudynumber);
		$numeric_only = str_pad($numeric_only, 10, "0", STR_PAD_LEFT);	
		
		$alpha_only = preg_replace("/[0-9]/", '', $tempStudynumber); 
		//$alpha_only = str_pad($numeric_only, 6, "0", STR_PAD_LEFT);		
		//echo $numeric_only;
		
		$studynumberList[$row_index]["alpha"] = $alpha_only;		
		$studynumberList[$row_index]["num"] = str_pad($numeric_only, 6, "0", STR_PAD_LEFT);	
		//$studynumberList[$row_index]["sort_num"] = $alpha_only . $numeric_only;
		$studynumberList[$row_index]["studynum"] = $tempStudynumber;		
		
		// echo str_pad($input, 10, "-=", STR_PAD_LEFT); 
		//echo $studynumber . "<br>";
		//echo "<li class='alphaTitleList'><A HREF= '" . $currentHTTP . "da_catalog_titleRecord.php?studynumber=$studynumber&title=$title'>$studynumber</a></li>";
		
	 	$row_index++;
	 		}
		
		sort($studynumberList);
	//echo "<h4  align='center'>";
	//print_r($studynumberList);
	
	for($row_index = 0; $row_index < count($studynumberList); $row_index++) {
		$studynumber = $studynumberList[$row_index]["studynum"];
		echo "<option value='" . $studynumber . "'>" . $studynumber . "</option>";
	}
	
		echo "</select><input type='submit' value='Get Record'>";
		echo "<input name='title' type='hidden' value='" . $title  . "'>";
		echo "</form>";
		
	
	// _destructor class closes connection
	// close the connection		
	//$PDO_connection = null;
	
	?>
</div>  <!--end content -->
</div>  <!--end container -->
 </body>

</html>


