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
<?php
	
	 //error_reporting(E_ALL);
//ini_set('display_errors', 1);

	$currentHTTP = "http://data-archive.library.ucla.edu/da_catalog/";	
	//SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
	include("../_includes/SSDA_librarydatabase.php");  //SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
	// class for database connections
	include "../_classes/class.Database.php";

	$indexTerm =  htmlspecialchars($_GET['indexTerm'], ENT_QUOTES); 
	 
	// check, if NOT set 
	if (empty($indexTerm)) { 
		echo "<span style='margin-left: 0; text-align: center; background-color: powderblue;'><a href='da_catalog_titles.php'>Return to catalog.</a></span><br>";
		die ("Nothing selected.");
		
		}
		
	// get the study number
	// sql query statement

//  NOTE: previous old query did not exclude the Restricted items, the new belwo query excludes item marked Restricted
$query = "select title.Title, title.StudyNum, shfull.subject FROM (title INNER JOIN shcode ON title.tisort = shcode.tisort) INNER JOIN shfull ON shcode.subjectcode = shfull.subjectcode WHERE (shfull.subject ='".$indexTerm."') and title.Restricted <> '*'  ORDER BY title.Title";
	
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


	 
	
	echo "<H2>$indexTerm</H2>";


		 
	
	echo "<ul>";

		while ($row = $db->getRow())  {
		// Non-PDO code ---------------------
		//while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			
			$title = $row[ "Title" ];
			$studynum = $row[ "StudyNum" ];
			//echo "<li class='alphaTitleList'><A HREF= '" . $currentHTTP . "da_catalog_titleRecord.php?studynumber=$studynum&title=$title'>$title</a></li>";
			echo "<li class='alphaTitleList'><A HREF= '" . $currentHTTP . "da_catalog_titleRecord.php?studynumber=$studynum'>$title</a></li>";
			
			
		}
	echo "</ul>";
	
	// _destructor class closes connection
	// close the connection		
	//$PDO_connection = null;
	
	?>  <!--end container -->
<!---------------------------------------------------------------------------------------------- -->

  <?php include("../_includes/SSDA_LibraryBottomPanel.php") ?>

</body></html>