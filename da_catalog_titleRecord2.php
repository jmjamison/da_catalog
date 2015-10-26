<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"

"http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<title>Data Archive: Dataset Record</title>


<link href="../_css/2col_leftNav.css" rel="stylesheet" type="text/css">


<body> 


<h1 id="siteName"><a href="index.php"><img src="../_images/logo75.jpg" width="75" height=""></a> Social Science Data Archive</h1> 

<div id="container">



<?php  include("../SSDA_menubar.php"); 
//
//SSDA_menubar.php has the menu code for da_catalog, da_catalog_fielder(fielder collection) and 'archive reources'
//?>

<br>
<!-- Scripts for title record menu bar -->
<script type="text/javascript" src="scripts/jquery-latest.js"></script> 
<script type="text/javascript" src="scripts/jquery.tablesorter.js"></script> 
<script type="text/javascript" id="js">$(document).ready(function() {
	// call the tablesorter plugin
	$("table").tablesorter({
		// sort on the first column and third column, order asc
		sortList: [[0,0],[2,0]]
	});
}); </script>

<!-- share-this script -->
<script type="text/javascript">var switchTo5x=true;</script><script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script><script type="text/javascript">stLight.options({publisher:'605fb740-c736-4f2d-b087-bd8ffdb0078f'});</script>

<div id="content" style="padding: 30px 50px 30px 50px;">  
<?php
 //error_reporting(E_ALL);
//ini_set('display_errors', 1);

	$currentHTTP = "http://data-archive.library.ucla.edu/da_catalog/";	
	include("SSDA_librarydatabase.php");
// Define configuration
// define info pulled from SSDA_librarydatabase.php
define("DB_HOST", $db_host);
define("DB_PORT", $db_port);
define("DB_USER", $db_username);
define("DB_PASS", $db_password);
define("DB_NAME", $db_name);
	
// should be adding "class.Database.php";	
function __autoload($class_name) {
	// echo 'class.' . $class_name . '.php<br>';
	include 'class.' . $class_name . '.php';
}	
	
	// for the record: I am using both title AND the study number, although I'm not displaying the studynumber -  to be sure I get the correct study
	//  Non-PDO code
	
	$studynumber =  $_GET['studynumber']; 
	//$title =  $_GET['title']; 
	//echo $title . " " . $studynumber . "<br>";
	//echo $studynumber . "<br>";
	
	$queryBaseRecord = "SELECT title.*, picode.picode, picode.tisubsort, pifull.*, shcode.subjectcode, shfull.*, title.StudyNum, wwwlink_pi.wwwlink AS wwwlink_pi, wwwlink_pi.wwwtext AS wwwtext_pi, wwwlink_subject.wwwlink AS wwwlink_subject, wwwlink_subject.wwwtext AS wwwtext_subject, cite.cite, pifull.picode, shcode.subsort, picode.tisubsort FROM cite RIGHT JOIN ((((((title LEFT JOIN picode ON title.tisort = picode.tisort) LEFT JOIN pifull ON picode.picode = pifull.picode) LEFT JOIN shcode ON title.tisort = shcode.tisort) LEFT JOIN shfull ON shcode.subjectcode = shfull.subjectcode) LEFT JOIN wwwlink AS wwwlink_subject ON shfull.subjectcode = wwwlink_subject.subjectcode) LEFT JOIN wwwlink AS wwwlink_pi ON pifull.picode = wwwlink_pi.picode) ON cite.citenum = title.Cite WHERE title.StudyNum='" . $studynumber . "'";
	
	//$queryDatasetRecords = "SELECT wwwlink.wwwlink, wwwlink.wwwtext, wwwlink.studynum, fileinfo.*, data_cite.cite AS datacite, fileinfo.Restricted AS restricted_file, fileinfo.StudyNum FROM cite AS data_cite RIGHT JOIN (wwwlink RIGHT JOIN fileinfo ON wwwlink.dtafile = fileinfo.DTAfile) ON data_cite.citenum = fileinfo.Cite WHERE fileinfo.StudyNum='" . $studynumber . "' ORDER BY fileinfo.StudyPart";
	
	$queryDatasetRecords = "SELECT wwwlink.wwwlink, wwwlink.wwwtext, wwwlink.studynum, fileinfo.*, data_cite.cite AS datacite, fileinfo.Restricted AS restricted_file, fileinfo.StudyNum FROM cite AS data_cite RIGHT JOIN (wwwlink RIGHT JOIN fileinfo ON wwwlink.dtafile = fileinfo.DTAfile) ON data_cite.citenum = fileinfo.Cite WHERE fileinfo.StudyNum='" . $studynumber . "' AND (FileType = 'CD-Rom' OR FileType = 'SDA Online Analysis' OR FileType = 'Web Access') ORDER BY fileinfo.StudyPart";

	
	
	//echo $queryDatasetRecords . "<br>"; 
	 
	// check, if NOT set 
	if (!isset( $_GET['studynumber'])) {  //   remove $title test, this will eventually be phased out as redundent -  or !isset($_GET['title'])  - jmj120409
		echo "<span style='margin-left: 0; text-align: center; background-color: powderblue;'><a href='mobindex.html'>No citations selected. Return to catalog.</a></span><br>";
		die ("No citations selected.");
		
		}
	
	// for the record: I am using both title AND the study number, although I'm not displaying the studynumber -  to be sure I get the correct study
	//  Non-PDO code
	
	//$studynumber =  $_GET['studynumber']; 
	//$title =  $_GET['title']; 
	
	//echo $title<br>$studynumber<br>";
	
// class.Database.php  is the class to make PDO connections
// initialize new db connection instance
$db = new Database();	 
	
// prepare query
$db->prepareQuery($queryBaseRecord);   	
	
// PDO - create prepared statement
//$PDO_query = $PDO_connection->prepare($queryBaseRecord);
	 
//$PDO_query->bindValue(":studynumber", $studynumber,  PDO::PARAM_STR);
//$PDO_query->bindValue(":title", $title,  PDO::PARAM_STR);
// bind value studynumber	
$db->bind(":studynumber",  $studynumber);	
	 
	 // PDO - execute the query
$result = $db->executeQuery();	
//$result = $db->resultset();  // execute the query
if (!$result) { 
		die ("Could not query the database: <br />"); 		
				}  // else {  echo "Successfully queried the database.<br>";   }  // for debugging
	
		//echo "<H2>$title&nbsp;&nbsp;($studynumber)</H2><br>";
	
		// complete record info
		$titleRecord = array();  // record - the title, sub# and restricted y/n
		// list of pi(s)
		
		$piList = array();		// list of pi(s)
		$piListFull = array();
		
		$subjectList = array(); // list of subjects
		//$fileInfo = array();		// file info -- move to part 2
		$usefulLinks = array();	// linklist
		//$linkListStudy = array();
		$linkListPI = array();
		$linkListSubject = array();
		
		$row_index = 0;   
		while  ($row = $db->getRow())  {     //   - 5 -
		// Non-PDO code ---------------------
		//while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			
			$titleRecord[$row_index]["title"] = $row["Title"];
			$titleRecord[$row_index]["studyNum"] = $row[ "StudyNum" ];
			$titleRecord[$row_index]["restricted"] = $row[ "Restricted" ];
			$titleRecord[$row_index]["www"] = $row[ "WWW" ];
			$titleRecord[$row_index]["citation"] = $row[ "cite"];
			$titleRecord[$row_index]["dataverseDOI"] = $row[ "dataverseDOI"];
			//print_r($titleRecord);
			//
			//
			// links connected to the studynum, pi or subject
			// used to populate the "useful links" list
			if (!empty($row["wwwlink_studynum"])) { 
			$linkListStudy[$row_index] = "<a href='http://" . $row["wwwlink_studynum"] . "'>" . $row["wwwtext_studynum"]  . "</a>";
				}
			//
			// database wierdness note: in the wwwlink table the wwwtext field is often NOT filled in for the PI so pick up the pi directly
			//      First grab the pi - just incase the wwwtext field is empty
			//      Then check if the wwwtext field is really empty, if it isn't put in the wwwtext
			if (!empty($row["wwwlink_pi"])) {
			$linkListPI[$row_index] = "<a href='http://" . $row["wwwlink_pi"] . "'>" . $row["pi"]  . "</a>";
				}
			if (!empty($row["wwwtext_pi"])) {
			$linkListPI[$row_index] = "<a href='http://" . $row["wwwlink_pi"] . "'>" . $row["wwwtext_pi"]  . "</a>";
				}
			//
			//
			if (!empty($row["wwwlink_subject"])) {
			$linkListSubject[$row_index] = "<a href='http://" . $row["wwwlink_subject"] . "'>" . $row["wwwtext_subject"] . "</a>";
			}
			
			
			// old version
			$piList[$row_index] = $row[ "pi"];			
					
			$piListFull[$row_index]["pi_tisubsort"] = $row[ "tisubsort"];
			$piListFull[$row_index]["pi"]  = $row[ "pi"];
			
			//
			$subjectList[$row_index]["subsort"] = $row[ "subsort"]; 
			$subjectList[$row_index]["subject"] = $row[ "subject"]; 
			// 
			$picode[$row_index] = $row["picode"];
			//
			$subjectcode[$row_index] = $row["subjectcode"];
			
			$row_index++;
		}      
		
		
		// 150814jmj: SORT-REGULAR  for array-to-string error
		$titleRecord = array_unique($titleRecord, SORT_REGULAR); 
		sort($titleRecord);
		$titleRecordCount = count($titleRecord);  // just to check
		//echo "record: $titleRecordCount<br>";
		//echo "For debugging purposes :";
		//($titleRecord);
		echo "<br>";
				
		$row_index = 0;
		$title = $titleRecord[$row_index]["title"];  // there is only going to be one title
		//echo $title;
		$studyNum = $titleRecord[$row_index]["studyNum"];  // also only one study number per study
		$restricted = $titleRecord[$row_index]["restricted"]; // ristricted also won't change
		$www = $titleRecord[$row_index]["www"];
		$citation = $titleRecord[$row_index]["citation"];
		$dataverseDOI = $titleRecord[$row_index]["dataverseDOI"];
		
		
	 	//$piList = array_unique($piList);	// toss out duplicates
		//sort($piList);
		//print_r($piList);
		$piTotal = count($piList);  
		//echo "Total Principal Investigators: $piTotal<br>";
		
		
		//sort($piListFull);
		$piListFullCount = count($piListFull);
		$subjectListCount = count($subjectList);
		
		$piList_temp = array();
		
		for ($row_index = 0; $row_index < $piListFullCount; $row_index++) {
			
			$piList_temp[$row_index] =  "(" .  $piListFull[$row_index]["pi_tisubsort"]  .   ") " . $piListFull[$row_index]["pi"];
		}
		$piList_temp = array_unique($piList_temp);
		sort($piList_temp);
		//echo "<br>PiListFull_temp sorted: ";
		//cho "<br>";
		//print_r($piList_temp);
		
		
		
		//$subjectList = array_unique($subjectList);	// toss out duplicates
		//sort($subjectList);
		$subjectListCount = count($subjectList);
		$subjectList_temp = array();
		
		for ($row_index = 0; $row_index < $subjectListCount; $row_index++) {
			
			$indexterm = $subjectList[$row_index]["subject"];
			$indexterm = "<a href='da_catalog_titlesByIndexTerm.php?indexTerm=" . $indexterm . "'>" . $indexterm . "</a>";
			
			$subjectList_temp[$row_index] =  "(" .  $subjectList[$row_index]["subsort"]  .   ") " . $indexterm;
		}
		
		$subjectList_temp = array_unique($subjectList_temp);
		$subjectList_tempCount = count($subjectList_temp);
		sort($subjectList_temp);
		$subjectList_temp = array_unique($subjectList_temp);
		$subjectList_tempCount = count($subjectList_temp);
		sort($subjectList_temp);
		//echo "<br>PiListFull_temp sorted: ";
		//echo "<br>";
		//print_r($subjectList_temp);
		
			
			echo "<H2>" . $title . "  (" . $studynumber . ")</H2><br>";
		
		
			echo "<h5>Subjects:  <h4>";
			
			if ($subjectList_tempCount < 2) {  // only 1 pi/principal investigator  
				$row_index=0;
				echo $subjectList_temp[$row_index];
				echo "<br>";
			} elseif ($subjectList_tempCount > 1) { // there is more than one pi name to print out 
				for ($row_index = 0; $row_index < $subjectList_tempCount-1; $row_index++) {
					echo $subjectList_temp[$row_index] . ", ";
				}
				$lastSubject = $subjectList_temp[$subjectList_tempCount-1]; // last subject doesn't have a comma after it
				echo "$lastSubject";
			}
			echo "</h4></h5>";
			
			echo "<br><br><h5>Principal Investigator(s):&nbsp;<h4>";			
		 
			$piList_tempCount = count($piList_temp);
			if ($piList_tempCount < 2) {  // only 1 pi/principal investigator  
				$row_index=0;
				echo "$piList_temp[$row_index]</h4>";
				echo "<br>";
			} elseif ($piList_tempCount > 1) { // there is more than one pi name to print out 
				for ($row_index = 0; $row_index < $piList_tempCount-1; $row_index++) {
				echo $piList_temp[$row_index] . ", ";
				}
				$lastPi = $piList_temp[$piList_tempCount-1]; // last pi doesn't have a comma after it
				echo "$lastPi";
			}
			
			
			echo "</h4></h5>";
			
			if ($citation <> "") {
				echo "<br><br><h5>Study Citation:&nbsp;</h5>" . $citation . "<br>";		
			}
			
			if ($dataverseDOI <> "") {
				echo "<br><br><h5>Download Here:&nbsp;<a href='" . $dataverseDOI . "'>" . $dataverseDOI . "</a></h5><br>";		
			}
			
			
			
			
	//--------------------------------------------------------------------------------------------
	//  Section for sharing links
	//  07/23/2011
	//
	//--------------------------------------------------------------------------------------------
	
	echo "<br>";
	
	echo "<div align='left' class='socialLinks' >";   
	
	echo "<span  class='st_twitter' ></span><span  class='st_facebook' ></span><span  class='st_email' ></span><span  class='st_sharethis' ></span>";
	
	echo "</div>";
	
	echo "<br>";
	//--------------------------------------------------------------------------------------------
	//  now do the 2nd pass for datasets\
	//
	//
	//--------------------------------------------------------------------------------------------
	
	$fileInfo = array();		// file info
	
	// PDO connect  
	//$PDO_string = "mysql:host=".$db_host.";dbname=da_catalog";
	//$PDO_string = "mysql:host=" . $db_host . ";port=" . $db_port . ";dbname=" . $db_name ;

	//echo "$PDO_string<br>";
	//echo $query . "<br>";
	
	
	// prepare query
$db->prepareQuery($queryDatasetRecords);   	
// execute query

//$PDO_query->bindValue(":studynumber", $studynumber,  PDO::PARAM_STR);
//$PDO_query->bindValue(":title", $title,  PDO::PARAM_STR);

// bind value studynumber	
$db->bind(":studynumber",  $studynumber);	
	 
// execute query
$result = $db->executeQuery();	 
	
//$result = $db->resultset();  // execute the query
if (!$result) { 
		die ("Could not query the database: <br />"); 		
		}  // else {  echo "Successfully queried the database.<br>";   }  // for debugging

	
	
	$row_index = 0;   
	while ($row = $db->getRow())  {     //    
		// Non-PDO code ---------------------
		//while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$fileInfo[$row_index]["studypart"] = $row[ "StudyPart" ];
		$fileInfo[$row_index]["dsname"] = $row[ "Dsname" ];
		$fileInfo[$row_index]["filetype"] = $row[ "FileType" ];
		$fileInfo[$row_index]["note"] = $row[ "Note" ];
		//$fileInfo[$row_index]["reclen"] = $row[ "Reclen" ];
		//$fileInfo[$row_index]["filesize"] = $row[ "Fullsize" ];
		$fileInfo[$row_index]["dtafilenum"] = $row[ "DTAfile" ];
		//  12-17-09jmj added the reformated DTAfile field, now dtafilename
		$fileInfo[$row_index]["dtafilename"] = $row[ "dtafilename" ];
		$fileInfo[$row_index]["datacite"] = $row["datacite"];
		// links for the DATASETS
		// part of the file information 
		//$fileInfo[$row_index]["wwwlink"] = $row["wwwlink"];
		//$fileInfo[$row_index]["wwwtext"] = $row["wwwtext"];
		$fileInfo[$row_index]["wwwlink"] = $row["wwwlink"];
		$fileInfo[$row_index]["wwwtext"] = $row["wwwtext"];
		//$fileInfo[$row_index]["restricted_file"] = $row["fileinfo.Restricted"];  		
		$fileInfo[$row_index]["restricted_file"] = $row["restricted_file"];  
		// restricted on the dataset level
	
		$row_index++;
		}  
		
		//echo "<br>";
		//print_r($fileInfo);
		//echo "<br>";
	
		$fileInfoCount = count($fileInfo);
		//echo "file row count: " . $fileInfoCount . "<br>";

	
	echo "<br>";
	
	if(count($fileInfo) >= 1) {
	
				
			//--------------------------------------------------------
			//echo "<br>";
			//echo "For material available to download from the Data Archve:<br>";
			echo "Click on the <b>File Name</b> to download an individual file.<br>";
			echo "<div id='downloadallfiles' class='datasets' >";
			//  12-15-09jmj
			//  if there is 1 or more - >= 1 - datasets that can be downloaded from the DATA ARCHIVE 
			//  after the dataset list is written out, javascript at the end of this section will write in thelines
			//   commented out below 
		//	echo "To download an entire study choose: <A HREF='addfiles.php?file=&study=" . $studynumber . "&src=" . $studynumber . "'><img src='addall.gif'></a><br><br>";
       		echo "</div><br>";
			echo "<div class='datasets' style = 'font-size: small'>Click category headers to sort.<br>";
			echo "<table id='datasetList' class='tablesorter'  style = 'font-size: small' cellpadding=5 >";
			echo "<thead><tr>";
			echo "<th class='label'  style = 'background-color: powderblue' width=100 >File Type</th>";
			echo "<th class='label'  style = 'background-color: powderblue' width=180 >Note</th>";
			echo "<th class='label'  style = 'background-color: powderblue' width=200 >Download Site</th>";	
			echo "<th class='label'  style = 'background-color: powderblue'>Dataset Citation</th>";
			echo "</tr></thead>";
			
	} 
			
		//  file info
		//print_r($fileInfo);
		
		//  INFO NOTE:  
		// ok, array_unique seems to not work too well with multi-dimensional arrays - see documentation on php site
		//  since the $dsname is the unique thingie here, just test on that below while looping through the array
		//$fileInfo = array_unique($fileInfo);
		//print_r($fileInfo);
		//echo "<br>";
		//sort($fileInfo);
		//print_r($fileInfo);
		//echo "<br>";
		//$fileInfoCount = count($fileInfo);  // just to check
		//echo "file information rows: $fileInfoCount<br>";
		$previousDtafilenum = null;
		
		// keep a count of data-archive-downloadable datasets, if >= 1 - at least 1 - write in the lines about Add All to the
		//     div section div id=downloadallfiles
		//     $da_downloadable is the counter variable
		//     use javascript script = -----
		//
	
			
		for ($row_index = 0; $row_index < $fileInfoCount; $row_index++)  {
			
		$studypart = $fileInfo[$row_index]["studypart"];
		$filetype = $fileInfo[$row_index]["filetype"];
		$note = $fileInfo[$row_index]["note"]; 
		$dsname = $fileInfo[$row_index]["dsname"];
		//if (empty($dsname)) { $dsname = ""; } // some ICPSR files don't use a dsname
		//$reclen = $fileInfo[$row_index]["reclen"];   
		//$filesize = $fileInfo[$row_index]["filesize"]; 
		$dtafilenum = $fileInfo[$row_index]["dtafilenum"];
		// 12-17-09jmj  added dtafilename, reformatedd DTAfile to dtafilename
		$dtafilename = $fileInfo[$row_index]["dtafilename"];
		//
		$datacite = $fileInfo[$row_index]["datacite"];
		$restricted_file = $fileInfo[$row_index]["restricted_file"];  // test for dataset level restricted file
		//  it is unlikely a base record will be unrestricted and a dataset restricted but this tests for that contingency
		
		$wwwtext = $fileInfo[$row_index]["wwwtext"];
		$wwwlink = $fileInfo[$row_index]["wwwlink"];
		
		
				
		//if ($dsname != $previousDSname) {
		if ($dtafilenum != $previousDtafilenum) {  
		
				echo "<TR>"; // beginning of the row
				
				// filetype
				if (stristr ($filetype, "ON CD-ROM"))	{  // datset on CD
					echo "<TD>" . $filetype . "</TD>";
					echo "<TD>$note: Please contact the Data Archives for data access assistance.</TD>";
					echo "<TD>&nbsp;</TD>"; 
					
				} elseif (stristr ($filetype, "SDA Online Analysis")) { // archive sda files (in-house); $filetype, $dsname, $note
				
					//$sda_onlineanalysis_link="http://zuma.sscnet.ucla.edu/issr/da/sdaweb/cgi-bin/hsda?harc";
					$sda_onlineanalysis_link="http://sda.sscnet.ucla.edu/cgi-bin/hsda?harc";
					echo "<TD>"  .  $filetype . "</TD>";  // which would be 'SDA online analysis' 
					echo "<TD>" . $note  . " ** SDA</TD>";     // something like the year, description, etc :  Codebook 1985-2001
					echo "<TD><a href='" . $sda_onlineanalysis_link . $dsname . "+" . $dsname . "'><h3>" . $filetype . " (" .  $dsname .  ") </h3><a></TD>";
					
				} elseif (stristr ($filetype, "Web Access")) { 
				
					if ((stristr($wwwtext, "ICPSR"))  OR (stristr ($note, "ICPSR")) OR (stristr ($filetype, "ICPSR"))) { 
					//  the most likely place to mark ICPSR is 1) the wwwtext field, 2) the note and 3) occasionally the filetype
					// this is an ICPSR dataset with a persistent link 
					//   reevaluate this section
					
						echo "<TD>"  .  $filetype . "</TD>";  // which would be 'Web Access' 
						
						echo "<TD>" . $note  . "</TD>";     // something like the year, description, etc :  Codebook 1985-2001
						
						//next, if the wwwlink has been left blank, build and fill in the persistant link
						// almost always this is the case where the ICPSRnnn number is in the wwwtext field and the wwwlink field is blank
						
						// download link section						
					 	if (empty($wwwlink)) { 
						
						//echo "wwwlink: " . $wwwlink . "<br>";
						
							$icpsrPersistentLink="http://dx.doi.org/10.3886/ICPSR0";
							//$icpsrStudynumber = substr($note, 5, strlen($note)-5);
							//$icpsrStudynumberLength = strlen($note)-5;
							$icpsrStudynumber = substr($wwwtext, 5, strlen($wwwtext)-5);
							$icpsrStudynumberLength = strlen($wwwtext)-5;
							echo "<TD><a href=" . $icpsrPersistentLink . $icpsrStudynumber ."><h3>" . $wwwtext  . "</h3><a></TD>";
							//------------------------------------------------------------------
							//    ELSE the link field -  wwwtext_link -  is NOT blank
							
								  } else  {// it's not blank
								  
								  //echo "<TD><a href=http://" . $wwwlink . "><h3>" . $wwwlink_text .  "</h3><a></TD>";
								 	//echo "wwwlink: " . $wwwlink . "<br>";
								  echo "<TD><a href='http://" . $wwwlink . "'><h3>" . $wwwtext .  "</h3><a></TD>";
								  }
						
						} else {  // any other site than ICPSR, no persistent link 
						
					
							echo "<TD>"  . $filetype . "</TD>";
							
							echo "<TD>" . $note . "<TD>";
							
							if (!empty($wwwlink)) { 
							//if (isset($wwwlink)) {
								echo " <a href=http://" . $wwwlink . " >" . $wwwtext  . "</a></TD>";
								//echo "<a href=http://" . $wwwlink . " >" . $note . " " . $wwwtext  . "</a></TD>";
							  		} else {
								  
								  	echo $wwwtext  . "  Please contact the Data Archives for data access assistance.</td>";
							 	 }
							  
							//echo "<TD>&nbsp;</TD>"; 
					
							}
				} 
				
				
				
				
				
			//	echo "<td>" . $datacite . "</td>";
				
				$previousDtafilenum = $dtafilenum;
				
				echo "</tr>";   // end of the row
				
				
				
				} // if-loop test for unique dsname
			
			
			
			} // for-loop for the entire file info array
			
			
			echo "</TABLE></DIV><br><br>";
			
			
			//-------------------------------------------------------------------------------------------------------------------
			
	// _destructor class closes connection
	// close the connection		
	//$PDO_connection = null;
	
	?>
    
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
