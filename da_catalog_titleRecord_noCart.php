<html>
<head><title>Study Titles: Alpha List</title>
<link rel="stylesheet" href="2col_leftNav.css" type="text/css">
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
<div id="masthead">
         <h1 id="siteName">UCLA Institute for Social Research Data Archives</h1> 

 <div id="globalNav"><div id="breadCrumb">
             <a href="http://www.sscnet.ucla.edu/issr/da/">Archive Home</a>|
             <a href="http://www.sscnet.ucla.edu/issr/da/da_catalog/">Archive Data Catalog </a>|
             <a href="http://www.sscnet.ucla.edu/issr/da/tutor/index.html">Archive Tutorial </a>|
             <a href="http://www.sscnet.ucla.edu/issr/da/resource.htm">Archive Resources </a>|
             <a href="http://www.icpsr.umich.edu">ICPSR</a>|
             <a href="http://www.sscnet.ucla.edu/issr/index.html">ISR Home </a>|
             <a href="http://www.ucla.edu/index.html">UCLA Home </a></div>
		
         </div>
</div> <!--end masthead--><!--end masthead-->

<div id="content">
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
	
	// for the record: I am using both title AND the study number, although I'm not displaying the studynumber -  to be sure I get the correct study
	//  Non-PDO code
	
	$studynumber =  $_GET['studynumber']; 
	$title =  $_GET['title']; 
	//echo $title . " " . $studynumber . "<br>";
	
	$query = "SELECT title.*, picode.picode, picode.tisubsort, pifull.*, shcode.subjectcode, shcode.subsort, shfull.*, wwwlink.wwwlink, wwwlink.wwwtext, wwwlink.studynum, fileinfo.*, title.StudyNum, wwwlink_pi.wwwlink AS wwwlink_pi, wwwlink_pi.wwwtext AS wwwtext_pi, wwwlink_studynum.wwwtext AS wwwtext_studynum, wwwlink_studynum.wwwlink AS wwwlink_studynum, wwwlink_subject.wwwlink AS wwwlink_subject, wwwlink_subject.wwwtext AS wwwtext_subject, cite.cite, data_cite.cite AS datacite FROM cite AS data_cite RIGHT JOIN (cite RIGHT JOIN ((((wwwlink RIGHT JOIN (((((title LEFT JOIN picode ON title.tisort = picode.tisort) LEFT JOIN pifull ON picode.picode = pifull.picode) LEFT JOIN shcode ON title.tisort = shcode.tisort) LEFT JOIN shfull ON shcode.subjectcode = shfull.subjectcode) LEFT JOIN fileinfo ON title.StudyNum = fileinfo.StudyNum) ON wwwlink.dtafile = fileinfo.DTAfile) LEFT JOIN wwwlink AS wwwlink_subject ON shfull.subjectcode = wwwlink_subject.subjectcode) LEFT JOIN wwwlink AS wwwlink_pi ON pifull.picode = wwwlink_pi.picode) LEFT JOIN wwwlink AS wwwlink_studynum ON fileinfo.StudyNum = wwwlink_studynum.studynum) ON cite.citenum = title.Cite) ON data_cite.citenum = fileinfo.Cite WHERE (((title.StudyNum)='" . $studynumber ."')) and title.Restricted <> '*' order by fileinfo.StudyPart";
	
	 
	// check, if NOT set 
	if (!isset( $_GET['studynumber']) or !isset($_GET['title'])) { 
		echo "<span style='margin-left: 0; text-align: center; background-color: powderblue;'><a href='mobindex.html'>No citations selected. Return to catalog.</a></span><br>";
		die ("No citations selected.");
		
		}
	
	// for the record: I am using both title AND the study number, although I'm not displaying the studynumber -  to be sure I get the correct study
	//  Non-PDO code
	
	$studynumber =  $_GET['studynumber']; 
	$title =  $_GET['title']; 
	
	//echo $title<br>$studynumber<br>";
	
	// PDO connect  
	//$PDO_string = "mysql:host=".$db_host.";dbname=da_catalog";
	$PDO_string = "mysql:host=" . $db_host . ";port=" . $db_port . ";dbname=" . $db_name ;

	//echo "$PDO_string<br>";
	//echo $query . "<br>";
	
	$PDO_connection = new PDO($PDO_string, $db_username, $db_password);
	
	// PDO - create prepared statement
	 $PDO_query = $PDO_connection->prepare($query);
	 
	$PDO_query->bindValue(":studynumber", $studynumber,  PDO::PARAM_STR);
	$PDO_query->bindValue(":title", $title,  PDO::PARAM_STR);
	 
	 // PDO - execute the query
	 $result = $PDO_query->execute();

	if (!$result) {
		die ("Could not query the database: <br />". mysql_error());
	}	
	
		//echo "<H2>$title&nbsp;&nbsp;($studynumber)</H2><br>";
	
		// complete record info
		$titleRecord = array();  // record - the title, sub# and restricted y/n
		// list of pi(s)
		
		$piList = array();		// list of pi(s)
		$piListFull = array();
		
		$subjectList = array(); // list of subjects
		$fileInfo = array();		// file info
		$usefulLinks = array();	// linklist
		$linkListStudy = array();
		$linkListPI = array();
		$linkListSubject = array();
		
		$row_index = 0;   
		while ($row = $PDO_query->fetch(PDO::FETCH_ASSOC))  {     //   - 5 -
		// Non-PDO code ---------------------
		//while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			
			$titleRecord[$row_index]["title"] = $row["Title"];
			$titleRecord[$row_index]["studyNum"] = $row[ "StudyNum" ];
			$titleRecord[$row_index]["restricted"] = $row[ "Restricted" ];
			$titleRecord[$row_index]["www"] = $row[ "WWW" ];
			$titleRecord[$row_index]["citation"] = $row[ "cite"];
			//print_r($titleRecord);
			//
			$fileInfo[$row_index]["dsname"] = $row[ "Dsname" ];
			$fileInfo[$row_index]["filetype"] = $row[ "FileType" ];
			$fileInfo[$row_index]["studypart"] = $row[ "StudyPart" ];
			$fileInfo[$row_index]["note"] = $row[ "Note" ];
			$fileInfo[$row_index]["reclen"] = $row[ "Reclen" ];
			$fileInfo[$row_index]["filesize"] = $row[ "Fullsize" ];
			$fileInfo[$row_index]["dtafilenum"] = $row[ "DTAfile" ];
			$fileInfo[$row_index]["datacite"] = $row["datacite"];
			// links for the DATASETS
			// part of the file information 
			//$fileInfo[$row_index]["wwwlink_file"] = $row["wwwlink_file"];
			//$fileInfo[$row_index]["wwwtext_file"] = $row["wwwtext_file"];
			$fileInfo[$row_index]["wwwlink_file"] = $row["wwwlink"];
			$fileInfo[$row_index]["wwwtext_file"] = $row["wwwtext"];
			//$fileInfo[$row_index]["restricted_file"] = $row["fileinfo.Restricted"];  // restricted on the dataset level
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
			//$linkList[$row_index] = "<a href='http://" . $row["wwwlink_studynum"] . "'>" . $row["wwwtext_studynum"]  . "</a><a href='http://" . $row["wwwlink_pi"] . "'>" . $row["wwwtext_pi"] . "</a><a href='http://" . $row["wwwlink_subject"] . "'>" . $row["wwwtext_subject"] . "</a>";
			
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
		
		$titleRecord = array_unique($titleRecord);
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
			
			$subjectList_temp[$row_index] =  "(" .  $subjectList[$row_index]["subsort"]  .   ") " . $subjectList[$row_index]["subject"];
		}
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
				echo "<br><br><h5>Study Citation:&nbsp;<h4>" . $citation . "</h4></h5>";		
			}
			
			// ----------------------------------
			// populate the 'useful links list' 
			//
			echo "<br><h5>Useful Links:</h5>";
			//print_r($linkListStudy);
			//print_r($linkListPI);
			//print_r($linkListSubject);
		
		
			if (count($linkListStudy) > 0)	{  // there is something in it
				$linkListStudy = array_unique($linkListStudy);
				foreach ($linkListStudy as $value) {
					
					if ($value <> "") {
						echo $value . "<br>";
					}
					
				}
			}
			
			if (count($linkListPI) > 0 ) {
				$linkListPI = array_unique($linkListPI);
				foreach ($linkListPI as $value) {
					
					if ($value <> "") {
						echo $value . "<br>";
					}
					
				}
			}
				
			if (count($linkListSubject) > 0 ) {
				$linkListSubject = array_unique($linkListSubject);
				foreach ($linkListSubject as $value) {
					
					if ($value <> "") {
						echo $value . "<br>";
					}
					
				}
			}
			
			echo "<br>";
			
				
			//print_r($fileInfo);
						
			//--------------------------------------------------------
			echo "<br><br>";
       		echo "<div class='datasets' style = 'font-size: small'>Click on the <b>File Name</b> to begin download process<br>";
			echo "<table style = 'font-size: small' cellpadding=5 >";
			echo "<th class='label' style = 'background-color: powderblue' width=100 >File Type</th>";
			echo "<th class='label' style = 'background-color: powderblue' width=200 >Note</th>";
			echo "<th class='label' style = 'background-color: powderblue' width=100 >File Name</th>";
			echo "<th class='label' style = 'background-color: powderblue'>Rec Len</th>";
			echo "<th class='label' style = 'background-color: powderblue'># Recs</th>";
			echo "<th class='label' style = 'background-color: powderblue'>File Size</th>";
			echo "<th class='label' style = 'background-color: powderblue'>Dataset Citation</th>";
			
			
		//  file info
		//print_r($fileInfo);
		
		//  INFO NOTE:  
		// ok, array_unique seems to not work too well with multi-dimensional arrays - see documentation on php site
		//  since the $dsname is the unique thingie here, just test on that below while looping through the array
		//$fileInfo = array_unique($fileInfo);
		//print_r($fileInfo);
		//echo "<br>";
		rsort($fileInfo);
		//print_r($fileInfo);
		//echo "<br>";
		$fileInfoCount = count($fileInfo);  // just to check
		//echo "file information rows: $fileInfoCount<br>";
		$previousDtafilenum = null;
			
		for ($row_index = 0; $row_index < $fileInfoCount; $row_index++)  {
		
		$filetype = $fileInfo[$row_index]["filetype"];
		$studypart = $fileInfo[$row_index]["studypart"];
		$note = $fileInfo[$row_index]["note"]; 
		$dsname = $fileInfo[$row_index]["dsname"];
		//if (empty($dsname)) { $dsname = ""; } // some ICPSR files don't use a dsname
		$reclen = $fileInfo[$row_index]["reclen"];   
		$filesize = $fileInfo[$row_index]["filesize"]; 
		$dtafilenum = $fileInfo[$row_index]["dtafilenum"];
		$datacite = $fileInfo[$row_index]["datacite"];
		//$restricted_file = $fileInfo[$row_index]["restricted_file"];  // test for dataset level restricted file
		//  it is unlikely a base record will be unrestricted and a dataset restricted but this tests for that contingency
		
		$wwwtext_file = $fileInfo[$row_index]["wwwtext_file"];
		$wwwlink_file = $fileInfo[$row_index]["wwwlink_file"];
		
		
				
		//if ($dsname != $previousDSname) {
		if ($dtafilenum != $previousDtafilenum) {  
		//  AND ($restricted_file <> "*")
		// use dtafile number rather than dsname to check for duplicates, less likely to be blank or a dup
		
				echo "<TR>"; // beginning of the row
				
				// filetype
				if (stristr ($filetype, "ON CD-ROM"))	{  // datset on CD
					echo "<TD>" . $filetype . "</TD>";
					echo "<TD>$note: Please contact the Data Archives for data access assistence.</TD>";
					echo "<TD>&nbsp;</TD>"; 
					
					
				} elseif (stristr ($filetype, "Web Access")) { 
				
					if ((stristr($wwwtext_file, "ICPSR"))  OR (stristr ($note, "ICPSR")) OR (stristr ($filetype, "ICPSR"))) { 
					//  the most likely place to mark ICPSR is 1) the wwwtext_file field, 2) the note and 3) occasionally the filetype
					// this is an ICPSR dataset with a persistent link 
					//   reevaluate this section
					
						echo "<TD>" .  $filetype . "</TD>";
						
						echo "<TD>" . $note  ."</TD>";
						
						//next, if the wwwlink has been left blank, build and fill in the persistant link
						// almost always this is the case where the ICPSRnnn number is in the wwwtext field and the wwwlink field is blank
						
						// download link section						
					 	if (empty($wwwtext_link)) { 
						
							$icpsrPersistentLink="http://dx.doi.org/10.3886/ICPSR0";
							//$icpsrStudynumber = substr($note, 5, strlen($note)-5);
							//$icpsrStudynumberLength = strlen($note)-5;
							$icpsrStudynumber = substr($wwwtext_file, 5, strlen($wwwtext_file)-5);
							$icpsrStudynumberLength = strlen($wwwtext_file)-5;
							echo "<TD><a href=" . $icpsrPersistentLink . $icpsrStudynumber .">" . $wwwtext_file  . "<a></TD>";
								  } else  {// it's not blank
								  
								  echo "<TD><a href=http://" . $wwwlink_file . ">" . $note . "<a></TD>";
								  }
						
						} else {  // any other site than ICPSR, no persistent link 
						
					
							echo "<TD>" . $filetype . "</TD>";
							
							echo "<TD>" . $note . "<TD>";
							
							if (!empty($wwwlink_file)) { 
							//if (isset($wwwlink_file)) {
								echo " <a href=http://" . $wwwlink_file . " >" . $wwwtext_file  . "</a></TD>";
								//echo "<a href=http://" . $wwwlink_file . " >" . $note . " " . $wwwtext_file  . "</a></TD>";
							  		} else {
								  
								  	echo $wwwtext_file  . "  Please contact the Data Archives for data access assistence.</td>";
							 	 }
							  
							//echo "<TD>&nbsp;</TD>"; 
					
							}
				} else { 
					$part_1 = trim(substr($dtafilenum, 0, 3));
					$part_2 = trim(substr($dtafilenum, -3));
					
					//echo "<br>$part_1   $part_2<br>";
					echo "<TD>$filetype</TD>";
					echo "<TD>$note</TD>";
					echo "<TD><A HREF=http://issrda.cluster.ucla.edu:10080/cgi-bin/main?DTA$part_1+$part_2+$dsname+$filesize+icpsr+$studyNum.HTM>$dsname</A></TD>"; 
					// DTA063+3
					}
				
				
				// reclen - record length, from tape format
				echo "<TD>";
				if ($reclen < 1) { // record length, left from tape format, if zero record length then print a blank
					$reclen = "";
					echo $reclen;
					} elseif ($reclen >= 1)	{
						echo $reclen;
					}
				echo "</TD>";
				
				
				$recnumber = "";   // record #, from tape days would go here
				echo "<TD>$recnumber</TD>";  
				
				// format the file size to K or MB
				if($filesize < 1) {   // is the filesize less then 1 ie. 0, the case for CD or Web
				
					$filesize = "";					
					echo "<td>" . $filesize . "</td>";
					
				}	else if ($filesize > 1048576) {
					
					$filesize = round($filesize/1048576, 2)."MB";
					echo "<td>" . $filesize . "</td>";
					
				} else {
					$filesize = round($filesize/1024, 1)."K";
					echo "<td>" . $filesize . "</td>";
				}
				
				echo "<td>" . $datacite . "</td>";
				
				$previousDtafilenum = $dtafilenum;
				
				echo "</tr>";   // end of the row
				
				
				
				} // if-loop test for unique dsname
			
			
			
			} // for-loop for the entire file info array
			
			
			echo "</TABLE></DIV><br><br>";
	
	// close the connection
	// mysql_close($connection);		
	$PDO_connection = null;
	
	?>
  
 </div> <!-- end content-->
 <div id="navBar"><div class="relatedLinks">
  <h3>Search By: </h3>
   <ul><h3>
   <li> <a href="da_catalog_index.php" title="Search by index terms">Index Terms</a></li>
    <li> <a href="da_catalog_titles.php" title="Search by study titles">Study Titles</a></li>
    <li> <a href="da_catalog_studynumbers.php" title="Search by study titles">Study Numbers</a></li></h3>
  </ul></div>
 </div>   <!--end navbar-->

<div id="siteInfo">
 <a href="http://www.sscnet.ucla.edu/issr/da/about.htm">About Us</a> |
 <a href="mailto:libbie@ucla.edu">Contact Us</a> |
  &copy;

07/07/2008 University of California
</div>

  </body></html>