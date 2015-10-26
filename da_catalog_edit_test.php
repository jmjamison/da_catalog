<html>
<head><title>Data Archive Catalog: Edit</title>
<link rel="stylesheet" href="2col_leftNav.css" type="text/css">
<!--  Javascript(s) to 1) paste PI names into text field, 2) clear the text fiels -->
<script language="JavaScript" src="da_catalog.js" type="text/javascript"><!--  da_catalog scripts //--></script>
<!-- end Javascript PI paste and clear -->

</head>
<body>
  <?php
		
	$sscnetHTTP = "http://www.sscnet.ucla.edu/issr/da/da_catalog/";
	$sscnetHTTP_maintenance_test = "http://www.sscnet.ucla.edu/issr/da/da_catalog_maintenance_test/";
	$sscnetHTTP_maintenance_live = "http://www.sscnet.ucla.edu/issr/da/da_catalog_maintenance/";
	$mydestopHTTP = "http://localhost/da_catalog/";
	$currentHTTP = $sscnetHTTP_maintenance_live;
	
	$sscnetInclude = "ISSRDA_login.php";
	$currentInclude = $sscnetInclude;
	include($currentInclude); 
	
	
	if (!empty($_POST['studynumber'])) {
		//echo "studynumber: " . $studynumber;
		$_SESSION['studynumber'] = $_POST['studynumber'];
		
			// something is selected
			$queryGetBaseRecord = "SELECT title.*, picode.ID as picode_id, picode.picode, picode.tisubsort,  pifull.*, shcode.ID as shcode_id, shcode.subjectcode, shcode.subsort as subject_subsort, shfull.*, wwwlink_pi.wwwlink AS wwwlink_pi, wwwlink_pi.wwwtext AS wwwtext_pi, wwwlink_subject.wwwlink AS wwwlink_subject, wwwlink_subject.wwwtext AS wwwtext_subject, cite.ID as citeID, cite.cite as cite_text, cite.subsort, pifull.picode FROM cite RIGHT JOIN ((((((title LEFT JOIN picode ON title.tisort = picode.tisort) LEFT JOIN pifull ON picode.picode = pifull.picode) LEFT JOIN shcode ON title.tisort = shcode.tisort) LEFT JOIN shfull ON shcode.subjectcode = shfull.subjectcode) LEFT JOIN wwwlink AS wwwlink_subject ON shfull.subjectcode = wwwlink_subject.subjectcode) LEFT JOIN wwwlink AS wwwlink_pi ON pifull.picode = wwwlink_pi.picode) ON cite.citenum = title.Cite WHERE (((title.StudyNum)='" . $studynumber ."'))";
			
	//echo "<br>" . $queryGetBaseRecord . "<br>";
			
	// PDO connect  
		$PDO_string = "mysql:host=" . $db_host . ";port=" . $db_port . ";dbname=" . $db_name ;

	 
	//  echo "<br>" .  $queryGetBaseRecord . "<br>";
			try	{
				$PDO_connection = new PDO($PDO_string, $db_username, $db_password);
		
				} catch(PDOException $e)	{
					echo "Could not connect to the database because: ".	$e->getMessage()."<br>";
					die();
					}
	
			// PDO - create prepared statement: get the table.pifull
 			// --------------------------------------------------------
			$PDO_query = $PDO_connection->prepare($queryGetBaseRecord);
			// PDO - execute the query
			$result = $PDO_query->execute();
	  		if (!$result) {
				die ("Could not query the database: <br />". mysql_error());
				} 	
			// complete record info
			$titleRecord = array();  // record - the title, sub# and restricted y/n
			// list of pi(s)
			$piList = array();		// list of pi(s)
			$piListFull = array();
			$current_pi = null;
			$temp = "";
			
			$subjectList = array(); // list of subjects
			$subjectListFull = array();
			$current_subject = null;
			
			$usefulLinks = array();	// linklist
			
			$row_index = 0;   
			while ($row = $PDO_query->fetch(PDO::FETCH_ASSOC))  {
		
			//----------------------------------------------------------------------------------------------------------------------------
			//
			//  there will only be one title record. these are essentially the fields from the title table
			//     because the query grabs everything in one big swoop the title record items will appear more than once even though there can only be
			//     one title record - the title record is the base record
			//    
			//     on the otherhand there can be multiple PIs, Subjects/Keywords and links. Those are handled separated. This note referes specifically to
			//          the title record
			//
			//---------------------------------------------------------------------------------------------------------------------------
				
				// relevant fields from the title and cite tables
				
				//$_SESSION['title'] = $row["Title"];
				// htmlspecialcharacters fixes situation where title string has quotes or other speical characters
				$_SESSION['title'] = htmlspecialchars($row["Title"]);
				$_SESSION['tisort'] = $row["tisort"];   // ID for title table, connecting field to the picode and shcode tables
				$_SESSION['restricted'] = $row[ "Restricted" ];
				$_SESSION['www'] = $row[ "WWW" ];
				$_SESSION['citeID'] = $row[ "citeID"];
				$_SESSION['cite_text'] = $row[ "cite_text"];
				$_SESSION['cite_subsort'] = $row["subsort"];
				if ($_SESSION['cite_subsort'] <= 1) { $_SESSION['cite_subsort'] = 1; }    // in the event that subsort is 0/not set, reset it to 1
				$_SESSION['sda'] = $row["SDA"];
				$_SESSION['varsrch'] = $row["Varsrch"];
				$_SESSION['justonCD'] = $row["JustOnCD"];
				$_SESSION['mobilityData'] = $row["mobilityData"];
				$_SESSION['eveFielderCollection'] = $row["eveFielderCollection"];
			
				
				$piList[$row_index] = $row[ "pi"];
				
				
				$temp_pi = $row["pi"];
				if ($temp_pi != $current_pi) {
					
					$piListFull[$row_index]["pi_tisubsort"] = $row["tisubsort"];  // pi list sorted by surbsort called tisubsort
					$piListFull[$row_index]["picode_id"] = $row["picode_id"];
					$piListFull[$row_index]["pi"] = $temp_pi;
					$piListFull[$row_index]["picode"] = $row["picode"];
					
					$current_pi = $temp_pi;	
					//echo "<br>" . $temp_pi . "<br>";
				}
				
				
			
				$subjectList[$row_index] = $row[ "subject"];
				
				$temp_subject = $row["subject"];
				if ($temp_subject != $current_subject) {
					
				$subjectListFull[$row_index]["subject_subsort"] = $row["subject_subsort"];   // this list sorts by subsort
					$subjectListFull[$row_index]["shcode_id"] = $row["shcode_id"];
					$subjectListFull[$row_index]["subject"] = $temp_subject;
					$subjectListFull[$row_index]["subjectcode"] = $row["subjectcode"];
					
					$current_subject = $temp_subject;
					//echo "<br>" . $temp_subject . "<br>";
				}
			
				$row_index++;
				
			}
			//print_r($subjectListFull);
			//echo "<br>";
			
			sort($piListFull);   // this will sort and re-number
			sort($subjectListFull);
			//print_r($piListFull);
			//echo "<br><br>";
			$piListFullCount = count($piListFull);
			$subjectListFullCount = count($subjectListFull);
						
			$piList = array_unique($piList);
			sort($piList);
			$piListCount = count($piList);
			//echo "<br>unique pi rows: " . $piListCount;
			
			//print_r($piList);
			
			if ($piListCount <= 1) {   //  just one pi
			
				$row_index = 0;
				
				$pi = $piList[$row_index];
				// I'm still using both of these
				$piTempList = $piList[$row_index];
				
				$_SESSION['pi'] = $piList[$row_index];
				
			} elseif ($piListCount > 1) { // there will be a list of PIs
			
					$piTempList = "";
					
					for ($row_index = 0; $row_index < $piListCount-1; $row_index++) {  // minus 1 so the last item won't have a ;
						
						$piTempList .= $piList[$row_index] . "; ";
						
						}
						
						$piTempList .= $piList[$row_index];
						
						//echo $piTempList . "<br>";
						
						$_SESSION["pi"] = $piTempList;
					
			}
			//echo $piTempList . "<br>";
			//$subjectListCount = count($piList);
			$subjectList = array_unique($subjectList);
			sort($subjectList);
			$subjectListCount = count($subjectList);			
			
			if ($subjectListCount <= 1) {   //  just one subject/keyword
			
				$row_index = 0;
				$_SESSION['subject'] = $subjectList[$row_index];
				
			} elseif ($subjectListCount > 1) { // there will be a list of PIs
			
					$subjectTempList = "";
					
					for ($row_index = 0; $row_index < $subjectListCount-1; $row_index++) {  // minus 1 so the last item won't have a ;
						
						$subjectTempList .= $subjectList[$row_index] . "; ";
						
						}
						
						$subjectTempList .= $subjectList[$row_index];
						
						//echo $subjectTempList . "<br>";
						
						$_SESSION["subject"] = $subjectTempList;
					
			}
					
			//echo "<br>" . $subjectTempList . "<br>";
						
			// close connection
			$PDO_connection = null;
			
			//
			
		
		
		
	} else 
		
			$studynumber = '';  
	
	//  add session variables so the pages stays populated
	//session_start();
	
	if (isset($_SESSION['studynumber']))
			$studynumber = $_SESSION['studynumber'];
	else 
			$studynumber = ''; 
			
	if (isset($_SESSION['title']))
			$title = $_SESSION['title'];
	else 
			$title = '';
			
	if (isset($_SESSION['tisort']))
			$tisort = $_SESSION['tisort'];
	else 
			$tisort = '';
			
	if (isset($_SESSION['pi']))
			$pi = $_SESSION['pi'];
	else 
			$pi = '';
	
	if (isset($_SESSION['subject']))
			$subject = $_SESSION['subject'];
	else 
			$subject = '';
	
	if (isset($_SESSION['citeID']))
			$citeID = $_SESSION['citeID'];
	else 
			$citeID = '';
	
	if (isset($_SESSION['cite_text']))
			$cite_text = $_SESSION['cite_text'];
	else 
			$cite_text = '';
			
	if (isset($_SESSION['cite_subsort']))
			$cite_subsort = $_SESSION['cite_subsort'];
	else 
			$cite_subsort = '';
			
	if (isset($_SESSION['wwwtext']))
			$wwwtext = $_SESSION['wwwtext'];
	else 
			$wwwtext = '';
			
	if (isset($_SESSION['wwwlink']))
			$wwwlink = $_SESSION['wwwlink'];
	else 
			$wwwlink = '';
			
	if (isset($_SESSION['sda']))
			$sda = $_SESSION['sda'];
	else 
			$sda = '';
			
	if (isset($_SESSION['varsrch']))
			$varsrch = $_SESSION['varsrch'];
	else 
			$varsrch = '';
			
	if (isset($_SESSION['restricted']))
			$restricted = $_SESSION['restricted'];
	else 
			$restricted = '';
			
	if (isset($_SESSION['justonCD']))
			$justonCD = $_SESSION['justonCD'];
	else 
			$justonCD = '';
			
	if (isset($_SESSION['mobilityData']))
			$mobilityData = $_SESSION['mobilityData'];
	else 
			$mobilityData = '';
			
	if (isset($_SESSION['eveFielderCollection']))
			$eveFielderCollection = $_SESSION['eveFielderCollection'];
			
	else 
			$eveFielderCollection = '';
			
	
	//echo "<br>" . $title . ", " . $sda . ", " . $justonCD . ", " . $varsrch . ", " . $mobilityData . ", " . $eveFielderCollection . "<br>";
	
	//$queryFileTypes = "SELECT distinct FileType FROM fileinfo where FileType != '' ORDER BY FileType";
$queryFileTypes = "SELECT DISTINCT fileinfo.FileType FROM fileinfo WHERE ((Not (fileinfo.FileType)='')) OR (((fileinfo.FileType) Is Not Null)) ORDER BY fileinfo.FileType";
	
	$queryTitleStudyNumFileType = "SELECT title.StudyNum, fileinfo.FileType, fileinfo.DTAfile FROM title LEFT JOIN fileinfo ON title.StudyNum = fileinfo.StudyNum ORDER BY title.StudyNum";
	
	
	
	// PDO connect  
	$PDO_string = "mysql:host=" . $db_host . ";port=" . $db_port . ";dbname=" . $db_name ;
	
	try	{
		$PDO_connection = new PDO($PDO_string, $db_username, $db_password); 
		
		} catch(PDOException $e)	{
			echo "Could not connect to the database because: ".	$e->getMessage()."<br>";
			die();
			}
	
			// PDO - create prepared statement: get the table.pifull
 			// --------------------------------------------------------
			$PDO_query = $PDO_connection->prepare($queryTitleStudyNumFileType);
			// PDO - execute the query
			$result = $PDO_query->execute();
	  		if (!$result) {
				die ("Could not query the database: <br />". mysql_error());
				} 	
			$fileTypeList = array();
			$studynumberList = array();
			$studynumberListTemp = array();
			$titleList = array();
			$dtafileList = array();
			
			$result = $PDO_query->fetch(PDO::FETCH_ASSOC); 
			
			$row_index = 0;   
			while ($row = $PDO_query->fetch(PDO::FETCH_ASSOC))  {
					
					$studynumberListTemp[$row_index] = $row["StudyNum"];
					if (!is_null($row["FileType"])) { $fileTypeList[$row_index] = $row["FileType"];  }  // others won't have nulls
						$dtafileList[$row_index] = $row["DTAfile"];
					
					$row_index++;
			}
			echo "<br>studynumber: " .  count($studynumberListTemp)  . "<br>";
			
			$row_index = 0;
			for ($row_index = 0; $row_index < count($studynumberListTemp); $row_index++)  {
		
				$tempStudynumber = $studynumberListTemp[$row_index];
		
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
		
	 			$row_index++;
	 			}
			echo "<br>studynumber: " .  count($studynumberList)  . "<br>";
			
			$studynumberList = array_unique($studynumberList);
			sort($studynumberList);
			$studynumberListTotal = count($studynumberList);
			
			$fileTypeList = array_unique($fileTypeList);
			sort($fileTypeList);
			$fileTypeTotal = count($fileTypeList);
			
			$dtafileList = array_unique($dtafileList);
			sort($dtafileList);
			
			
			
			$fileTypeListText = implode("','", $fileTypeList);
			$fileTypeListText = "'" . $fileTypeListText . "'";
			
			
//--------------------------------------------------------------------
//  Section: populate the PI listbox
//      used for authority control, javascript select_PIs will paste in selected fields
//----------------------------------------------------------------------
$queryPI_list = "SELECT DISTINCT pi FROM pifull ORDER BY pi";
// echo text for debugging purposes only
	
	// PDO connect  
	$PDO_string = "mysql:host=" . $db_host . ";port=" . $db_port . ";dbname=" . $db_name ;
	
	try	{
		$PDO_connection = new PDO($PDO_string, $db_username, $db_password); 
		
		} catch(PDOException $e)	{
			echo "Could not connect to the database because: ".	$e->getMessage()."<br>";
			die();
			}
	
			// PDO - create prepared statement: get the table.pifull
 			// --------------------------------------------------------
			$PDO_query = $PDO_connection->prepare($queryPI_list);
			// PDO - execute the query
			$result = $PDO_query->execute();
	  		if (!$result) {
				die ("Could not query the database: <br />". mysql_error());
				} 	
			$PI_list = array();
			
			
			$result = $PDO_query->fetch(PDO::FETCH_ASSOC); 
			$row_index = 0;   
			
			while ($row = $PDO_query->fetch(PDO::FETCH_ASSOC))  {
					
					//$PI_list[$row_index]["picode"] = $row["picode"];
					$PI_list[$row_index]["pi"] = $row["pi"];
					$row_index++;
			}
			
			$PI_list_count = count($PI_list);
			// debugging purposes only
			//echo "PI count: " . $PI_list_count . "    "; /
			
//--------------------------------------------------------------
//       end populate PI section
//-------------------------------------------------------------

//--------------------------------------------------------------------
//  Section: populate the Subject/Index-term listbox
//      used for authority control, javascript to copy and paste in selected fields
//----------------------------------------------------------------------
$querySubject_list = "SELECT DISTINCT subject FROM shfull ORDER BY subject";
//  echo for debugging purposes only
	
	// PDO connect  
	$PDO_string = "mysql:host=" . $db_host . ";port=" . $db_port . ";dbname=" . $db_name ;
	
	try	{
		$PDO_connection = new PDO($PDO_string, $db_username, $db_password); 
		
		} catch(PDOException $e)	{
			echo "Could not connect to the database because: ".	$e->getMessage()."<br>";
			die();
			}
	
			// PDO - create prepared statement: get the table.pifull
 			// --------------------------------------------------------
			$PDO_query = $PDO_connection->prepare($querySubject_list);
			// PDO - execute the query
			$result = $PDO_query->execute();
	  		if (!$result) {
				die ("Could not query the database: <br />". mysql_error());
				} 	
			$Subject_list = array();
			
			
			$result = $PDO_query->fetch(PDO::FETCH_ASSOC); 
			$row_index = 0;   
			
			while ($row = $PDO_query->fetch(PDO::FETCH_ASSOC))  {
					
					$Subject_list[$row_index]["subject"] = $row["subject"];
					$row_index++;
			}
			
			$Subject_list_count = count($Subject_list);
			// debugging purposes only
			//echo "Subject count: " . $Subject_list_count . "<br>";
//--------------------------------------------------------------
//       end populate Subject section
//-------------------------------------------------------------
			
				
?>

<div id="masthead">
<h1 id="siteName">UCLA Institute for Social Research Data Archives</h1> 
          <h2 id="siteName"><a href="index.php" target="_self">Maintenance Menu</a>&nbsp;/&nbsp;Edit Base Record</h2>  
</div> <!--end masthead--><!--end masthead-->

<div style="margin: 1% 5% 2% 2%;line-height: 1.5;	">
  <form <?php 
  
  	if (isset($_SESSION['studynumber'])) {     //   a study has been chose so post means go to updateCheck
			echo " action='da_catalog_updateCheck.php'";  
			
		}   else {    //  a study HAS NOT been chosen so post the study info back to the input page when you do choose a study
				echo " action=''";  
				}
	
	?>    method="post" name="updateRecord" target="_self">
  
  
    <p><label>Studynumber: 
     <?php
		   		if (isset($_SESSION['studynumber'])) {
					
					$studynumber = $_SESSION['studynumber'];
					
					echo '<input name="studynumber" type="text" id="studynumber" value="' . $studynumber . '" size="30" maxlength="255">';
					
					
					echo '</select>';
					
					
				} else {
			
					echo '<select name="studynumber" id="studynumber">';
					echo '<option value="">choose studynumber</option>';
					//foreach ($studynumberList as $key => $value) {
				
						//echo '<option value="' . $value . '">' . $value;
				
				 	//}
					for($row_index = 0; $row_index < count($studynumberList); $row_index++) {	
						
						$studynumber = $studynumberList[$row_index]["studynum"];
						
						echo '<option value="' . $studynumber . '">' . $studynumber;
					}
					
			echo " <input name='getbaserecord' type='submit' id='getbaserecord' value='Get this Studynumber'>";
				}
			
			
							
		?></label>
   
    <input name="updateTitle" type="submit" id="updateTitle" value="save/update record">
    <input name="reset form" type="reset" value="reset">
    <input type="button" onClick="history.go(0)" value="Reload page">
    <br><br>
      <!--<input name="title" type="hidden" value="title"> -->
       
       <label>Title: <input name="title" type="text" value="<?php echo $title ?>" size="100" maxlength="225">
       </label><input name="tisubsort" type="hidden" value="1">
       
          <br><br>
      <label>Title level citation: (Subsort number: <?php echo $cite_subsort ?>) citeID: <input name="cite_subsort" type="hidden" value="<?php echo $citeID ?>"><?php echo $citeID ?><br>
      <textarea name="cite_text" type="text" cols="150"><?php echo $cite_text ?></textarea></label>  
      <br>
      <input name="cite_subsort" type="hidden" value="<?php echo $cite_subsort ?>">
      
   
      <label>Restricted:<input name="restricted" type="checkbox"  value="*" <?php if ($restricted == "*") { echo "CHECKED"; }   ?>  >
      </label> 
       
       <label>SDA:<input name="sda" type="checkbox" value="*" <?php if ($sda == "*") { echo "CHECKED"; }   ?>  >
       </label>
       &nbsp;&nbsp;
       <label>Varsrch:<input name="varsrch" type="checkbox" value="*" <?php if ($varsrch == "*") { echo "CHECKED"; }   ?>  >
       </label>
       
       &nbsp;&nbsp;
      <label>CD Only:
         <input name="justonCD" type="checkbox" id="justonCD" value="*" <?php if ($justonCD == "*") { echo "CHECKED"; }   ?>  >
       </label>
       &nbsp;&nbsp;
       <label>
         <input name="article" type="hidden" value="">
       </label>
       &nbsp;&nbsp;
       <label>Mobility Data:<input name="mobilityData" type="checkbox" id="mobilityData" value="*" <?php if ($mobilityData == "*") { echo "CHECKED"; }   ?>  >
       </label> <label>Eve Fielder Collection:<input name="eveFielderCollection" type="checkbox" id="eveFielderCollection" value="*" <?php if ($eveFielderCollection == "*") { echo "CHECKED"; }   ?>  >
       </label></p>
       <hr>
  </form><br>
  
 
 <form action='da_catalog_processFormCheck.php' method='post' name='editPI'> 
 <label>Principal Investigators (PIs) currently linked to <?php echo $studynumber;  ?>: </label> <?php echo $pi  ?><br>
         <?php if (isset($_SESSION['studynumber'])) {
					
					//echo "<br>";
					$studynumber = $_SESSION['studynumber'];
					
					sort($piList);
					$piListCount = count($piList);
					//echo "<br>unique pi rows: " . $piListCount . "<br>";
			
					
						for ($row_index = 0; $row_index < $piListFullCount; $row_index++) {
							
							$pi = $piListFull[$row_index]["pi"];
							$picode = $piListFull[$row_index]["picode"];
							$picode_id = $piListFull[$row_index]["picode_id"];
							$pi_tisubsort = $piListFull[$row_index]["pi_tisubsort"];
							
							//echo "<form action='da_catalog_processFormCheck.php' method='post' name='editPI'>";
							echo "<input name='pi' type='text' value='" . htmlspecialchars($pi, ENT_QUOTES) . "'  size='100' maxlength='255'>";
							echo "<input name='studynumber' type='hidden' value='" .  $studynumber  . "' >";
							echo "<input name='picode' type='text' value='pifull: " .  $picode  . "' >";
							echo "<input name='picode_id' type='text' value='connection: " .  $picode_id  . "' >";
							echo "<input name='item_id' type='hidden' value='" .  $picode_id  . "' >";
							echo "<input name='tisort' type='hidden' value='" .  $tisort  . "' >";
							echo "<input name='requestCategory' type='hidden' value='" . ($pi) . "'>";
							// the name below is the connecting table for pi
							echo "<input name='table_name' type='hidden' value='picode'>";
							//
							echo "<input name='pi_tisubsort' type='hidden' value='" . $pi_tisubsort . "'  size='3' maxlength='3'>";
							echo "<input name='item_subsort' type='text' value='" . $pi_tisubsort . "'  size='3' maxlength='3'>";
							echo "<input name='item_subsort_fieldname' type='hidden' value='tisubsort'>";
							//
							echo "<input name='requestType' type='submit' value='Update sort number'>     <input name='requestType' type='submit' value='Delete link'><br>";
							
							
							//onclick='" . deleteThing('picode', $picode_id)  . "'
							
						}
								
				} 
				
        
         ?></form>
         
         
 <form action="" method="post" name="editPI_LIST">
 <label>Principal Investigators currently available</label><br>
 
        
<!-- 23-March-2010/jmj commenting out the clear field button, once cleared cant be reloaded with out going back reselecting page -->
       <!-- <input name="clearOutPI_list" type="button" id="clearOutPI_list" onClick="clearoutPI_list('editPI_LIST', 'pi')" value="clear out PI list"> -->
    <select name="pi_list" size="10" multiple id="pi_list">
         <!--
        //--------------------------------------------------------------------------
        //   dropdown box that is populated with PI names
        //         used as authority control
        //         pick from list and paste into text box/pi with javscript/select_pi
        //--------------------------------------------------------------------------
         -->
         <?php
	   		
			for ($row_index=0; $row_index < $PI_list_count; $row_index++) {
				//$picode = $PI_list[$row_index]["picode"];
				$pi =  $PI_list[$row_index]["pi"];
				
				echo '<option value="' . $pi . '">' .$pi;
				
				 }
		?>
         <!--
         //---------------------------------------------------------------------------------
     	//  end of pi list box
    	 //------------------------------------------------
          -->
    </select>
 </form> 
 
 <form action="da_catalog_insertPICheck.php" method="post" name="addPI" target="_self"> 
  <label>Add [One] Principal Investigator (PI): </label>  
      <input name='pickPIs' type='button' id='pickPIs' onClick="selectPasteCopy('editPI_LIST', 'pi_list', 'pi_new', 'addPI')" value='Select and Paste a SINGLE PI in text box below'>
      
      <input name='addPI' type='submit' value='Add A Single New Principle Investigator (PI)'> 
      <?php if (isset($_SESSION['studynumber'])) {
					
					echo "<br>";
					$studynumber = $_SESSION['studynumber'];
					echo "<input name='studynumber' type='hidden' value='" .  $studynumber  . "' >    ";
				} 
         ?>
      <input name='tisort' type='hidden' value='<?php echo $tisort ?>' >
      <!-- the pi_tisubsort will contain whatever the last value was, use to derive the next tisubsort when creating a new picode record -->
      <input name='pi_tisubsort' type='hidden' value='<?php echo $pi_tisubsort ?>"' size='3' maxlength='3'>
      <input name='title' type='hidden' value='<?php echo $title ?>' >
      <input name='pi_new' type='text' value=''  size='200' maxlength='255'>		
      <!--<input name='subject_new' type='text' value=''  size='200' maxlength='255'> -->
   
  </form>
  <!--end of the pi section -->
  <br>
  
  <!-- beginning of the subject/index section -->
<form action="da_catalog_processFormCheck.php" method="post" name="editSubject" >
<label>Subject/Index or Keyword Terms currently linked to <?php echo $studynumber; ?>: </label><?php echo $subject  ?>
   
<!--<br><input name='subjectList' type='text' value='<?php echo $subject  ?>' size='200' maxlength='255'><br><br>
 <!-- <br><input name='subject_new' type='text' value=''  size='200' maxlength='255'> -->
  <!--<input name="pickSubjects" type="button" onClick="selectPasteCopy('editSubject', 'subject_list', 'subjectList')"  value="Select and Paste to Subject List"><br>
  <input name='pickSubjects2' type='button' onClick="selectPasteCopy('editSubject', 'subject_list', 'subject_new')" value='Select and Paste a SINGLE Subject/Keyword in text box below'> -->
<!--<label>Select Term(s) from list:</label><br>
<strong>Note</strong>: It is possible to select <em>MULTIPLE</em> subject/keyword terms but in order to add terms in a <strong>specific order</strong>, select and paste <em>each term Individually</em>.  --><br> 
  <?php if (isset($_SESSION['studynumber'])) {
					
					//echo "<br>";
					$studynumber = $_SESSION['studynumber'];
					
					//sort($subjectListFull);
					//$subjectListFull = array_unique($subjectListFull);
					sort($subjectListFull);
					$subjectListFullCount = count($subjectListFull);
					//echo "<br>unique subject rows: " . $subjectListFullCount . "<br>";
			
					
						for ($row_index = 0; $row_index < $subjectListFullCount; $row_index++) {
							
							$subject = $subjectListFull[$row_index]["subject"];
							$shcode_id = $subjectListFull[$row_index]["shcode_id"];
							$subjectcode = $subjectListFull[$row_index]["subjectcode"];
							$subject_subsort = $subjectListFull[$row_index]["subject_subsort"];
							$currentSubject = null;
							
							if ($subjectcode != $currentSubjectCode) {
							echo "<input name='subject' type='text' value='" . $subject . "'  size='100' maxlength='255'>";
							echo "<input name='studynumber' type='hidden' value='" .  $studynumber  . "' >";
							echo "<input name='tisort' type='hidden' value='" .  $tisort  . "' >";
							echo "<input name='subjectcode' type='text' value='subjectcode: " .  $subjectcode  . "' >";
							echo "<input name='shcode_id' type='text' value='shcode_id: " .  $shcode_id  . "' >";
							echo "<input name='item_id' type='hidden' value='" .  $shcode_id  . "' >";
							echo "<input name='requestCategory' type='hidden' value='subject'>";
							// name below is the connecting table for the subjects
							//
							echo "<input name='subject_subsort' type='hidden' value='" . $subject_subsort . "'  size='3' maxlength='3'>";
							echo "<input name='item_subsort' type='text' value='" . $subject_subsort . "'  size='3' maxlength='3'>";
							//
							echo "<input name='table_name' type='hidden' value='shcode'>";
							echo "<input name='item_subsort_fieldname' type='hidden' value='subsort'>";
							echo "<input name='requestType' type='submit' value='Update sort number'>     <input name='requestType' type='submit' value='Delete link'>";
							//echo "   " . $row_index;
							$currentSubjectCode = $subjectcode;
							}
							
						}
								
				} 
        
         ?>
    
    <br><br>
      <!--
        //--------------------------------------------------------------------------
        //   dropdown box that is populated with Subject/Keyword terms
        //         used as authority control
        //         pick from list and paste into text box/pi with javscript (da_catalog.js library)  selectPasteCopy and clearoutTextElement scripts functions
        //--------------------------------------------------------------------------
         -->
 <label>Subjects/Index Terms currently available</label><br>
      <select name="subject_list" size="10" multiple id="subject_list">
       
        <?php
	   		
			for ($row_index=0; $row_index < $Subject_list_count; $row_index++) {
				$subject =  $Subject_list[$row_index]["subject"];
				
				echo '<option value="' . $subject . '">' . $subject;
				
				 }   
		?>
      </select>
      <!--
         //---------------------------------------------------------------------------------
     	//  end of subject list box
    	 //------------------------------------------------
          -->
  </form>
    
<form action="da_catalog_insertSubjectCheck.php" method="post" name="addSubject" target="_self"> 
<label>Add [One] Subject/Keyword  to <?php echo $studynumber;  ?></label> 

<input name='pickSubjects2' type='button' onClick="selectPasteCopy('editSubject', 'subject_list', 'subject_new', 'addSubject')" value='Select and Paste a SINGLE Subject/Keyword in text box below'>

<input name='addSubject' type='submit' value='Add New Subject/Keyword'> 

<input name='subject_new' type='text' value=''  size='200' maxlength='255'>
      <?php if (isset($_SESSION['studynumber'])) {
					
					echo "<br>";
					$studynumber = $_SESSION['studynumber'];
					
					//$subjectList = array_unique($subjectList);
					//sort($subjectList);
					//$subjectListCount = count($subjectList);
					//print_r($piList);
			
							//echo "<h4>";		
							//for ($row_index = 0; $row_index < $piListCount-1; $row_index++) {  // minus 1 so the last item won't have a ;
							//foreach ($subjectList as $key => $value) {
						
								//echo $value . "     <a href='' >edit</a>     <a href='' >delete</a><br>";
						
								//}
								
						
						echo "<input name='studynumber' type='hidden' value='" .  $studynumber  . "' >    ";
						echo "<input name='tisort' type='hidden' value='" .  $tisort  . "' >"; // the connecting field for title/picode/pifull
						//echo "</h4>";	
				} 
        	
         ?>
       
</form>      
  <!-- end of the subject/index section -->
     
 <?php  
 
 // close the connection
	// mysql_close($connection);		
	$PDO_connection = null;
	
	?>
 
</div> <!-- end content-->
 
  </body></html>