<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"

"http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<title>Data Archvie Catalog</title>

<link href="../_css/2col_leftNav.css" rel="stylesheet" type="text/css">

<body> 


<h1 id="siteName"><a href="../index.php"><img src="../_images/logo75.jpg" width="75" height=""></a> Social Science Data Archive</h1> 

<div id="container">

<?php  

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

	$currentHTTP = "http://data-archive.library.ucla.edu/da_catalog/";	
	//SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
	include("../_includes/SSDA_librarydatabase.php");  //SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
	// class for database connections
	include "../_classes/class.Database.php";
	
	include("../_includes/SSDA_menubar.php");  
//
// SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
//


//

//?>

<div id="content" style="padding: 30px 50px 30px 50px;">  


  <h1 align="center" style="color: #006699;">Archive Catalog</h1><br>
  
  <P>This is the catalog of <B style="color: #006699;">all</B> studies at the Data Archives. You 
    may <B style="color: #006699;">Search</b> by broad <B style="color: #006699;"><a href="da_catalog_index.php" title="Search by index terms">Index</a></b> terms that we use to categorize 
    the collection; by words in study <B style="color: #006699;"><a href="da_catalog_titles.php" title="Search by study titles">Titles</a></b>; by the most recently 
    received studies or <B style="color: #006699;">Updates</b>; or you can do a keyword <B style="color: #006699;">Search</b>. 
    A keyword <B style="color: #006699;">Search</b> will look for words that appear anywhere in 
    the information about a study. You should also search the <a href="http://www.icpsr.umich.edu/access/index.html">ICPSR</a> 
    catalog, where you can narrow your search and find studies more precisely. 
    For questions, please contact the <a href="mailto:libbie@ucla.edu">Archive</a>.</P>
  
 <h3 align="center"><A HREF = "da_catalog_index.php">index</A>&nbsp;&bull;&nbsp;<A HREF = "da_catalog_titles.php">titles</A>&nbsp;&bull;&nbsp;<A HREF = "da_catalog_studynumbers.php">study numbers</A> &nbsp;&bull;&nbsp;<A HREF = "da_catalog_search.php">keyword/subject search</A></h3>
  
</div>  <!--end content -->
</div>  <!--end container -->
 
 <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-19063567-4', 'auto');
  ga('send', 'pageview');

</script>
 


</body>

</html>
