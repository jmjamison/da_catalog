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
<H2 align="center">Data Archives Catalog Keyword Search</H2>

<?php
	
		

	//error_reporting(E_ALL);
//ini_set('display_errors', 1);

	$currentHTTP = "http://data-archive.library.ucla.edu/da_catalog/";	
	//SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
	include("../_includes/SSDA_librarydatabase.php");  //SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
	// class for database connections
	include "../_classes/class.Database.php";
	
	$searchTerm = "";
	$searchPhrase = "";
	
	echo "<div align='center' style='padding: 30px 50px 30px 50px;'><form action='da_catalog_SearchResults.php?searchTerm=$searchTerm' method='put' name='searchTerm' target='_self'>";

	echo "<table border='1' bordercolor='#FFFFCC' bordercolorlight='#66CCFF' bordercolordark='#66CCFF' bgcolor='#FFFFCC'  id='search'>";

	echo "<tr><td><label>Text to Search For: </label></td><td><input type=text name='searchTerm' size=40></td></tr>";
	
	echo "<tr><td colspan=2 style='text-align: center;'><input type=submit value='Search!'> <input type=reset></th></tr></table></form></div>";


	
	?>
    <p>For any questions please contact <a href='mailto:libbie@ucla.edu'>Elizabeth Stephenson.</a></p>  <!--end container -->
<!---------------------------------------------------------------------------------------------- -->

 <?php include("../_includes/SSDA_LibraryBottomPanel.php") ?>

</body></html>