<html>
<head><title>Data Archives Catalog Studynumber List</title>
<link rel="stylesheet" href="2col_leftNav.css" type="text/css">
<script src="SpryAssets/SpryAccordion.js" type="text/javascript"></script>
<link href="SpryAssets/SpryAccordion.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {color: #333333}
body {
	background-color: #FFFBEC;
}
.style2 {
	font-size: large;
	font-weight: bold;
}
.style5 {color: #333333; font-weight: bold; }
-->
</style>
</head>
<body>
<!-- link to top of page starts here -->
<div id="masthead">
         <h1 id="siteName">Social Science Data Archive</h1> 

<div id="globalNav"><div id="breadCrumb">
             <a href="http://dataarchives.ss.ucla.edu/">Archive Home</a>|
             <a href="http://dataarchives.ss.ucla.edu/da_catalog/">Archive Data Catalog </a>|
             <a href="http://dataarchives.ss.ucla.edu/tutor/index.html">Archive Tutorial </a>|
             <a href="http://dataarchives.ss.ucla.edu/resource.htm">Archive Resources </a>|
             <a href="http://www.icpsr.umich.edu">ICPSR</a>|
             <!--<a href="http://www.sscnet.ucla.edu/issr/index.html">ISR Home </a>| -->
             <a href="http://www.ucla.edu/index.html">UCLA Home </a></div>
		
         </div>
</div> <!--end masthead--><!--end masthead-->

<div id="content"><H1 align="center">Study Number List</H1>

Studynumber list<br>

<?php
	
	$sscnetHTTP = "http://www.sscnet.ucla.edu/issr/da/da_catalog/";
	$mydestopHTTP = "http://localhost/da_catalog/";
	$currentHTTP = $sscnetHTTP;
	
	//include the login file - db_login.php
	//include('../db_login2.php');
	//include('ISSRDA_login.php');
	// set the include file
	//include the login file - db_login.php
	$sscnetInclude = "ISSRDA_login.php";
	$mydesktopInclude = "../db_login2.php";
	$currentInclude = $sscnetInclude;
	include($currentInclude); 
	
	// for desktop test system
	//$db_name = "da_catalog";	
	
		
	// PDO connect  
	//$PDO_string = "mysql:host=".$db_host.";dbname=da_catalog";
	$PDO_string = "mysql:host=" . $db_host . ";port=" . $db_port . ";dbname=" . $db_name ;
	// echo "$PDO_string<br>";
	$PDO_connection = new PDO($PDO_string, $db_username, $db_password);
	
	// sql query statement  

	// NOTE: original query did not exclude the Restricted items
	// $query = "select title.Title, title.StudyNum, shfull.subject, Left(shfull.subject,1) AS firstLetterIndex, count(*) as titlePerSubjectCount FROM (title INNER JOIN shcode ON title.tisort = shcode.tisort) INNER JOIN shfull ON shcode.subjectcode = shfull.subjectcode group by shfull.subject ORDER BY shfull.subject";
	
	// New query excludes items marked Restricted
	
	$query = "select title.StudyNum, title.Title, title.Restricted FROM title  ORDER BY title.StudyNum";
	
	 
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
		// Non-PDO code ---------------------
		
		$studynumber = $row['StudyNum'];
		$title = $row["Title"];
		$restricted = $row["Restricted"];
		
		
		echo $restricted . "  ";
		echo $studynumber . "  ";
		echo $restricted . "  ,";
		echo $title . "<br>";
		
	
		
			//echo "<li class='alphaTitleList'><A HREF= '" . $currentHTTP . "da_catalog_titleRecord.php?studynumber=$studynumber&title=$title'>$studynumber</a></li>";
         
         
		
	 	$row_index++;
	 		}
		
	
	// close the connection
	// mysql_close($connection);		
	$PDO_connection = null;
	
	
	//print_r($indexList); 
	//echo "<br>";
	//print_r($indexFirstLetterList);
	?>
</div> 
<!-- end container-->
 <div id="navBar"><div class="relatedLinks">
  <h3>Search By: </h3>
   <ul><h3>
  <li> <a href="da_catalog_index.php" title="Search by index terms">Index Terms</a></li>
    <li> <a href="da_catalog_titles.php" title="Search by study titles">Study Titles</a></li>
    <li> <a href="da_catalog_studynumbers.php" title="Search by study titles">Study Numbers</a></li>
    <li> <a href="da_catalog_search.php" title="Search Database">Keyword or Term</a></li></h3>
  </ul></div>
 </div>   <!--end navbar-->

</body></html>