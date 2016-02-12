<!DOCTYPE html>
 <?php include("../_includes/SSDA_LibraryTopPanel.php") ?>
 
<title>Social Science Data Archive | UCLA Library</title>
  
 <?php include_once("../_includes/SSDA_LibrarySidePanel.php") ?>
 
<div class="panel-pane pane-bean-text-block pane-bean-ssda-schedule-appointment">
  
      
  
  <div class="pane-content">
    <div class="entity entity-bean bean-text-block clearfix">

  <div class="content">
    <div class="field field--name-field-text-block field--type-text-long field--label-hidden"><div class="field__items"><div class="field__item even"><p>Have questions about your research? <a href="mailto:libbie@g.ucla.edu?subject=Research%20questions">We can help?</a></p>
</div></div></div>  </div>
</div>
  </div>

  
  </div>
    </div>


  
  <div class="l-region l-region--main-column">
  
     
<!-- data archive  menubar - library in-house version  -->
<?php  
	include("../_includes/SSDA_menubar_libraryInHouse.php");  
//
// SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
//
?>
<!-- data archive google analytics tracking script -->
<?php include_once("../_includes/analyticstracking.php") ?>

  
          <div class="l-region l-region--main">
        <div class="panel-pane pane-node-body">
  

  
  <div class="pane-content">
  
  
    <div class="field field--name-body field--type-text-with-summary field--label-hidden"><div class="field__items"><div class="field__item even">



<!---------------------------------------------------------------------------------------------- -->
<!--ssda page code goes here -->
<H2 align="center">Study Numbers</H2>

<p>
Search by Study Number.  For any questions please contact <a href='mailto:libbie@ucla.edu'>Elizabeth Stephenson.</a> </p>


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

$query = "select title.StudyNum, title.Title FROM title where title.Restricted <> '*' ORDER BY title.StudyNum";
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
	
	
	
	
	// the index term list
	$studynumberList=array();
	
	echo "<form action='da_catalog_titleRecord.php' method='put' name='studynumber' target='_self'>";
	echo  "<select name='studynumber' class='alphaTitleList'>";
    
	
		
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
		//echo "<input name='title' type='hidden' value='" . $title  . "'>";
		echo "</form>";
		
		
	
	// _destructor class closes connection
	// close the connection		
	//$PDO_connection = null;
	
	?> <!--end container -->
<!---------------------------------------------------------------------------------------------- -->

 <?php include("../_includes/SSDA_LibraryBottomPanel.php") ?>

  

</body></html>