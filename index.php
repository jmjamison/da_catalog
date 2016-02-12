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
<div id="container" style="padding: 30px 50px 30px 50px;">

<?php  

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

	$currentHTTP = "http://data-archive.library.ucla.edu/da_catalog/";	
	//SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
	include("../_includes/SSDA_librarydatabase.php");  //SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
	// class for database connections
	include "../_classes/class.Database.php";
//
// SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
//


//

//?>

<!--<div id="content" style="padding: 30px 50px 30px 50px;"> -->




 <!-- <h1 align="center" style="color: #006699;">Archive Catalog</h1> -->
  
  <P>This is the catalog of <B style="color: #006699;">all</B> studies at the Data Archives. You 
    may <B style="color: #006699;">Search</b> by broad <B style="color: #006699;"><a href="da_catalog_index.php" title="Search by index terms">Index</a></b> terms that we use to categorize 
    the collection; by words in study <B style="color: #006699;"><a href="da_catalog_titles.php" title="Search by study titles">Titles</a></b>; by the most recently 
    received studies or <B style="color: #006699;">Updates</b>; or you can do a keyword <B style="color: #006699;">Search</b>. 
    A keyword <B style="color: #006699;">Search</b> will look for words that appear anywhere in 
    the information about a study. You should also search the <a href="http://www.icpsr.umich.edu/icpsrweb/ICPSR/">ICPSR</a> 
    catalog, where you can narrow your search and find studies more precisely. 
    For questions, please contact the <a href="mailto:libbie@ucla.edu">Archive</a>.</P>
  
 <h3 align="center"><A HREF = "da_catalog_index.php">index</A>&nbsp;&bull;&nbsp;<A HREF = "da_catalog_titles.php">titles</A>&nbsp;&bull;&nbsp;<A HREF = "da_catalog_studynumbers.php">study numbers</A> &nbsp;&bull;&nbsp;<A HREF = "da_catalog_search.php">keyword/subject search</A>&nbsp;&bull;&nbsp;<A HREF = "https://dataverse.harvard.edu/dataverse/ssda_ucla">Dataverse</A></h3>

</div>  <!--end container -->

  <?php include("../_includes/SSDA_LibraryBottomPanel.php") ?>

</body></html>