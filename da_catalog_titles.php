<!DOCTYPE html>
 <?php include("../_includes/SSDA_LibraryTopPanel.php") ?>
 
<title>Social Science Data Archive | UCLA Library</title>

  
 <?php include("../_includes/SSDA_LibrarySidePanel.php") ?>
 
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
<!-- -->
<!-- -->
<H2 align="center">Titles That Begin With The Letter...</H2><br>

 <?php
 
 //error_reporting(E_ALL);
//ini_set('display_errors', 1);

	$currentHTTP = "http://data-archive.library.ucla.edu/da_catalog/";	
	//SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
	include("../_includes/SSDA_librarydatabase.php");  //SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
	// class for database connections
	include "../_classes/class.Database.php";
	
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
	echo "</table><br>";
	
	// _destructor class closes connection
	// close the connection		
	//$PDO_connection = null;
		
	?>
</div>  <!--end content -->
</div>  <!--end container -->  <!--end container -->
<!---------------------------------------------------------------------------------------------- -->
<!--  end of da_catalog content -->

 <?php include("../_includes/SSDA_LibraryBottomPanel.php") ?>
  

</body></html>