<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"

"http://www.w3.org/TR/html4/loose.dtd">

<html>


<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<title>Data Archive Catalog: Index Terms</title>


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
//
//SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
//?>


<br>
<div id="content" style="padding: 30px 50px 30px 50px;">  
<H1 align="center">Index Terms</H1>
<table width='85%' align="center" cols=1><tr align="left"><td align="left">
These are broad terms we use to organize the collection into categories. You should also search the <a href='http://www.icpsr.umich.edu/access/index.html'>ICPSR catalog</a> where you can narrow your search and find studies more precisely. To navigate this page you may scroll the list, or click on a letter to search for terms beginning with that letter. For any questions please contact <a href='mailto:libbie@ucla.edu'>Elizabeth Stephenson.</a> </td></tr></table><br>
<A NAME="IND"></A>

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
	
	// sql query statement  

	// old query did not exclude the items marked Restricted
	// $query = "select title.Title, title.StudyNum, shfull.subject, Left(shfull.subject,1) AS firstLetterIndex, count(*) as titlePerSubjectCount FROM (title INNER JOIN shcode ON title.tisort = shcode.tisort) INNER JOIN shfull ON shcode.subjectcode = shfull.subjectcode group by shfull.subject ORDER BY shfull.subject";
	// NEW query excludes the itmes marked Restricted
	$query = "select title.Title, title.StudyNum, shfull.subject, Left(shfull.subject,1) AS firstLetterIndex, count(*) as titlePerSubjectCount FROM (title INNER JOIN shcode ON title.tisort = shcode.tisort) INNER JOIN shfull ON shcode.subjectcode = shfull.subjectcode  where title.Restricted <> '*'  group by shfull.subject ORDER BY shfull.subject";
	
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
		}  // else {  echo "Successfully queried the database.<br>";
	
	// the index term list
	$indexList=array();
	// first letter of indext term list
	$indexFirstLetterList=array();

	
	$row_index = 0;
	while ($row = $db->getRow())  {
		// Non-PDO code ---------------------
		
		$indexList[$row_index]["title"] = $row['Title'];
		$indexList[$row_index]["studynum"] = $row['StudyNum'];
		$indexList[$row_index]["indexTerm"] = $row['subject'];
		$indexList[$row_index]["firstLetterIndex"] = $row['firstLetterIndex'];
		$indexList[$row_index]["titlePerSubjectCount"] = $row['titlePerSubjectCount'];
	 	$indexFirstLetterList[$row_index] = $row['firstLetterIndex'];
	 	$row_index++;
	 		}
	
		$totalRows = count($indexFirstLetterList);
	
	//  build the first letter list
	echo "<h2  align='center'>";
	
	
	$indexFirstLetterList = array_unique($indexFirstLetterList);
	sort($indexFirstLetterList);
	foreach($indexFirstLetterList as $key => $value) {
		
	echo "|<A HREF='#$value'>&nbsp;$value&nbsp;</A>";
		//echo " | $index_letter ";
	}
	echo "|</h2><br>";
	echo "</br>";
		
		
//  print out the subject terms, sort by first letter
	
//----------------------------------------------------------------------------
//
//  begin the for-loop to read through the entire array
//
//----------------------------------------------------------------------------	
		
		//echo "<tr><td>$subject</td></tr>";		
	//}
	
	$totalFirstLetters = count($indexFirstLetterList);
	$firstLetterPrevious = NULL;
	//echo "first letter total: $totalFirstLetters<br>";
	echo "<ul  style='text-align: left; margin: 30; margin-left: 50;'>";      // start of the ul list	 
	//foreach($indexFirstLetterList as $key => $firstLetter )	{ 
	for ($row_index = 0; $row_index < $totalRows; $row_index++ ) {		
					
	 	$title = $indexList[$row_index]["title"]; 
		$studynum = $indexList[$row_index]["studynum"];
		$indexTerm = $indexList[$row_index]["indexTerm"];	
	 	$firstLetterIndex = $indexList[$row_index]["firstLetterIndex"];
		$titlePerSubjectCount = $indexList[$row_index]["titlePerSubjectCount"];
		
	 	if ($firstLetterPrevious != $firstLetterIndex ) {	// ie. this is the FIREST time printing the the first Letter -  echo gettype($value), "\n";
		// previous unsafe code - country=$country'
	 		echo "<br><br><h2 style='text-align: left;'><a id='$firstLetterIndex'>$firstLetterIndex</a></h2>";		
			echo "</ul>"; // end previous
			echo "<ul style='text-align: left; margin: 30; margin-left: 50;'>";  // start new list
			$firstLetterPrevious = $firstLetterIndex ;    // so you can compare the previous value of $firstLetterIndex with the provious one
	 		}
		echo "<li type='disc'>&nbsp;<a href='";
		// - if there is only 1 record then link to that record
		// -- otherwise if there is more than one record, list the titles
			if ($titlePerSubjectCount > 1) {
				echo $currentHTTP . "da_catalog_titlesByIndexTerm.php?indexTerm=$indexTerm";
			}	else  {
				echo $currentHTTP . "da_catalog_titleRecord.php?studynumber=$studynum&title=$title";
			}
		echo "'>$indexTerm</a></h4>";
		// - decision to print titlE or titleE(S) 
		echo "&nbsp;($titlePerSubjectCount&nbsp;";
			if ($titlePerSubjectCount > 1) {
				echo "titles)";
			}	else  {
					echo "title)";
			}
		echo "";
		echo "</li>";
			
	}	
//----------------------------------------------------------------------------
//
//   end of the for-loop
//
//----------------------------------------------------------------------------
		
		echo "</ul>";      // end of the ul list
		// put link to return to top of page ---	
		echo "<a href='#top'>Return to top of the page</a>";
		

//----------------------------------------------------------------------------
//
//   end of the for-loop
//
//----------------------------------------------------------------------------
		
	
	// close the connection
	// mysql_close($connection);		
	$PDO_connection = null;
	
	// _destructor class closes connection
	// close the connection		
	//$PDO_connection = null;
	
	?>
</div>  <!--end content -->
</div>  <!--end container -->
</body>

</html>
