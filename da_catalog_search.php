<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"

"http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<title>Data Archive Catalog: Search Catalog</title>


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

<H1 align="center">Data Archives Catalog Search Engine</H1>
<br>

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
	
	echo "<div align='center'><form action='da_catalog_SearchResults.php?searchTerm=$searchTerm' method='put' name='searchTerm' target='_self'>";

	echo "<table border='1' bordercolor='#FFFFCC' bordercolorlight='#66CCFF' bordercolordark='#66CCFF' bgcolor='#FFFFCC'  id='search'>";

	echo "<tr><td><label>Text to Search For: </label></td><td><input type=text name='searchTerm' size=40></td></tr>";
	
	echo "<tr><td colspan=2 style='text-align: center;'><input type=submit value='Search!'> <input type=reset></th></tr></table></form></div>";


	
	?>
    <p>For any questions please contact <a href='mailto:libbie@ucla.edu'>Elizabeth Stephenson.</a></p>
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


