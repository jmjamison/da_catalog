<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"

"http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<title>Data Archive Catalog: Title List</title>


<link href="../_css/2col_leftNav.css" rel="stylesheet" type="text/css"><body>

<?php  include("../SSDA_menubar.php"); 
//
//SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
//?>

<div id="content" style="padding: 30px 50px 30px 50px;">  <H1 align="center">Study Numbers</H1>

Search by Study Number.  For any questions please contact <a href='mailto:libbie@ucla.edu'>Elizabeth Stephenson.</a> </td></tr></table><br>
<A NAME="IND"></A>

<?php
	
	//error_reporting(E_ALL);
	//ini_set('display_errors', 1);

	$currentHTTP = "http://data-archive.library.ucla.edu/da_catalog/";	
	include("SSDA_librarydatabase.php"); 
	
	$query = "select title.StudyNum, title.Title FROM title where title.Restricted <> '*' ORDER BY title.StudyNum";
	
	$queryForM-numbers = "select title.StudyNum, title.Title, title.tisort FROM title where (title.StudyNum LIKE 'M%') AND (title.Studynum >= 'M1150V1') ORDER BY title.StudyNum";
	
	 
	 // PDO - create prepared statement
	 $PDO_query = $PDO_connection->prepare($query);
	 // PDO - execute the query
	 $result = $PDO_query->execute();
	 
	 if (!$result) {
		die ("Could not query the database: <br />". mysql_error());
	}
	
	
	echo "<h2  align='center'>";
	
	// the index term list
	$studynumberList=array();
	
	

		
		$row_index = 0;
		
	while ($row = $PDO_query->fetch(PDO::FETCH_ASSOC))  {
		
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
		
		$studynumberList[$row_index]["title"] = $row["Title"];
		$studynumberList[$row_index]["tisort"] = $tow["tisort"];
		
		
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
		$tisort = $studynumberList[$row_index]["tisort"];
		$title = $studynumberList[$row_index]["title"];
		
		echo $studynumber . "   " . $tisort . "   " . $title . "<br>";
		
	}
	
	
		
	
	// close the connection
	// mysql_close($connection);		
	$PDO_connection = null;
	
	
	//print_r($indexList); 
	//echo "<br>";
	//print_r($indexFirstLetterList);
	?>
</div>  <!--end content -->
</div>  <!--end container -->

</body></html>