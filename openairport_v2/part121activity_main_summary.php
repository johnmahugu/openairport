<?
/*	= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = 
	
	Page Name						Purpose :
	Summary Report General.php			The Summary Report General Page takes a supplied set of arrays and strings and makes a generic report displayed inline with the OpenAirport layout specified in header.php
	
								Usage:
								This page should work in most cases, but in those cases where it wont, this page should be used as the template for your custome page. When using a custom page you will need to
								account for the new name in the Settings of the Browse and Entry pages of the applicable module.
							

							
	NOTE: THERE SHOULD BE NO NEED TO CHANGE ANY OF THE CODE ON THIS PAGE
	
	= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = 
*/	
	// Load Required Include Files
	
		include("includes/header.php");															// This include 'header.php' is the main include file which has the page layout, css, and functions all defined.
		include("includes/POSTs.php");															// This include pulls information from the $_POST['']; variable array for use on this page
		//include("includes/NavFunctions.php");																// already included in header.php
		//include("includes/UserFunctions.php");																// already included in header.php
		
	// Build the BreadCrum trail which shows the user their current location and how to navigate to other sections.
	
		buildbreadcrumtrail($strmenuitemid,$frmstartdate,$frmenddate);

	// Get string information from the POST process of the form which action was this page
	
		$tblkeyfield			= $_POST['tblkeyfield'];
		$tblarchivedfield		= $_POST['tblarchivedfield'];		
		$tbldatesortfield		= $_POST['tbldatesortfield'];											
		$tbldatesorttable		= $_POST['tbldatesorttable'];											
		$tbltextsortfield		= $_POST['tbltextsortfield'];											
		$tbltextsorttable		= $_POST['tbltextsorttable'];											
		$functioneditpage		= $_POST['editpage'];													
		$functionsummarypage	= $_POST['summarypage'];												
		$functionprinterpage	= $_POST['printerpage'];												
		//$sql 					= $_POST['frmurl'];															
		$menuitemid 			= $_POST['menuitemid'];													
		$tblname				= $_POST['tblname'];													
		$tblsubname				= $_POST['tblsubname'];													

	// Take the seralized array which was submited with the Form and build the a new array which can be used by this page for performing actions
	// There are two phases, (1). Get the information from the POST and replace | with ", this is needed due to how the information is sent via the POST process.
	// Step (2). is to take the string replaced serialized array and rebuild it into an actuall array.
	
		$adatafield 			= unserialize(str_replace("|","\"",$_POST['adatafield']));				
		$adatafieldtable 		= unserialize(str_replace("|","\"",$_POST['adatafieldtable']));			
		$adatafieldid 			= unserialize(str_replace("|","\"",$_POST['adatafieldid']));				
		$adataspecial			= unserialize(str_replace("|","\"",$_POST['adataspecial']));			
		$aheadername			= unserialize(str_replace("|","\"",$_POST['aheadername']));				
		$ainputtype				= unserialize(str_replace("|","\"",$_POST['ainputtype']));				
		$ainputcomment			= unserialize(str_replace("|","\"",$_POST['ainputcomment']));			
		$adataselect			= unserialize(str_replace("|","\"",$_POST['adataselect']));				
	
	// Take the unserialized array and make serialized arrays for use in the FORMS on this page.
	// This is just the reverse of the previouse step, and although may not be required keeps all of the pages uniform in function and appereance.
	
		$sadatafield			= serialize($adatafield);
		$sadatafield			= str_replace("\"", "|",$sadatafield);
		$sadatafieldtable 		= serialize($adatafieldtable);											
		$sadatafieldtable 		= str_replace("\"","|",$sadatafieldtable);								
		$sadatafieldid 			= serialize($adatafieldid);												
		$sadatafieldid 			= str_replace("\"","|",$sadatafieldid);									
		$sadataspecial			= serialize($adataspecial);												
		$sadataspecial			= str_replace("\"","|",$sadataspecial);									
		$saheadername			= serialize($aheadername);												
		$saheadername			= str_replace("\"","|",$saheadername);									
		$sainputtype			= serialize($ainputtype);												
		$sainputtype			= str_replace("\"","|",$sainputtype);									
		$sainputcomment			= serialize($ainputcomment);											
		$sainputcomment			= str_replace("\"","|",$sainputcomment);								
		$sadataselect			= serialize($adataselect);												
		$sadataselect			= str_replace("\"","|",$sadataselect);									
	
	// Build SQL Statement
	// Since we have all of the information from the arrays and strings we can assemble the nessary SQL Statement without actually having to supply it verbatum.
	// The Limiting clauses will be left off, all we want to create at this point is the basic SELECT *,*,* FROM syntax
	
		$sql = "SELECT ".$tblkeyfield.",";														// Start SQL adding on the id field first						
		
		for ($i=0; $i<count($adatafield); $i=$i+1) {											// Loop through any of the arrays 0 to array length - 1
				$nsql = " ".$adatafield[$i]."";													// add each new value in the array to a temporary sql string
				$sql = $sql.$nsql;																// add the temporary sql string to the main sql string.
				if ($i == count($adatafield)-1) {												// test to see where we are in the string, in this case are we at the end or not?
						$nsql = "";																// at the end of the arrat dont add a , to the end of the value
						$sql = $sql.$nsql;														// add 'nothing' to the sql string
					}
					else {																		// not at the end of the array so do soemthing else
						$nsql = ", ";															// for each value in the array that is not at the end, add a , after the value
						$sql = $sql.$nsql;														// add the temporary sql string to the main sql string
					}			
		}
		$sql = $sql.$nsql;																		// field index values have all been added to the sql string (this line is reduntent, but there for space)
		$nsql = " FROM ".$tbldatesorttable." ";													// with all field index values added, add the FROM syntax and the applicable table in the DB
		$sql = $sql.$nsql;																		// make it all one nice sql string for use

	// For debugging purposes print out the SQL Statement
		//echo $sql;																				// When dedugging you can uncomment this echo and see the sql statement

	// Start the Real Fun	
	
		$i = 0;																					// just in case we want the i variable to be defined before we use it

if (!isset($_POST["recordid"])) {																// Test to see if $_POST['recordid'] exists, and if not do
		echo "no record id";																	// this should never be seen if this page is used correctly
	}
	else {																						// $_POST['recordid'] does exist, so lets use it
		
		$nsql =" WHERE ".$tblkeyfield." = ".$_POST["recordid"]."";								// Add the record id POST value to the sql temporary string
		$sql = $sql.$nsql;																		// add the temporary string to the main sql statement		
		//echo $sql;																				// When dedugging you can uncomment this echo and see the sql statement
		
		$objconn = mysqli_connect("localhost", "webuser", "limitaces", "openairport");				// make a connection with the openairport database
				
		if (mysqli_connect_errno()) {															// if there is an error making the connection inform the user
				// there was an error trying to connect to the mysql database
				printf("connect failed: %s\n", mysqli_connect_error());							// tell the user the error message
				exit();
			}
			else {																				// without any errors...
				$objrs = mysqli_query($objconn, $sql);											// create the query recordsource
						
				if ($objrs) {																	// if the recordsource is created without error do...
						$number_of_rows = mysqli_num_rows($objrs);								// How many rows did the sql statement find
						?>
						<br>
						<table border="0" width="100%" id="tblbrowseformtable" cellspacing="0" cellpadding="0">
							<tr>
								<td width="10" class="tableheaderleft">&nbsp;</td>
								<td class="tableheadercenter">
									<?=$tblname;?>
									</td>
								<td class="tableheaderright">
									(<?=$tblsubname;?>)
									</td>
								</tr>
							<tr>
								<td colspan="3" class="tablesubcontent">
									<table border="0" width="100%" cellspacing="3" cellpadding="5" id="table2" height="10">
										<tr>
											<td colspan="2">
												<table cellspacing="0" width="100%">
													<tr>
														<td class="formoptionsavilabletop">
															The following options are avilable to you
															</td>
														</tr>
													<tr>
														<td class="formoptionsavilablebottom">
															<table>
													<tr>
														<?
														$encoded = urlencode($sql);
														?>
														<form style="margin-bottom:0;" action="part121activity_sub_a.php" method="POST" name="summaryform" id="summaryform" target="PrinterFriendlyWindow" onsubmit="window.open('', 'PrinterFriendlyWindow', 'width=511,height=550,status=no,resizable=no,scrollbars=yes')">
														<td class="formoptionsubmit">
															<input type="hidden" name="recordid" 			value="<?=$_POST['recordid'];?>">
															<input type="hidden" name="menuitemid" 			value="<?=$_POST['menuitemid'];?>">
															<input type="hidden" name="editpage" 			value="<?=$functioneditpage;?>">
															<input type="hidden" name="summarypage" 		value="<?=$functionsummarypage;?>">
															<input type="hidden" name="printerpage" 		value="<?=$functionprinterpage;?>">
															<input type="hidden" name="tblname" 			value="<?=$tblname;?>">
															<input type="hidden" name="tblsubname" 			value="<?=$tblsubname?>">
															<input type="hidden" name="aheadername" 		value="<?=$saheadername;?>">
															<input type="hidden" name="adatafield" 			value="<?=$sadatafield;?>">
															<input type="hidden" name="adatafieldtable" 	value="<?=$sadatafieldtable;?>">
															<input type="hidden" name="adatafieldid" 		value="<?=$sadatafieldid;?>">
															<input type="hidden" name="adataspecial" 		value="<?=$sadataspecial;?>">
															<input type="hidden" name="ainputtype" 			value="<?=$sainputtype;?>">
															<input type="hidden" name="ainputcomment" 		value="<?=$sainputcomment;?>">
															<input type="hidden" name="adataselect" 		value="<?=$sadataselect;?>">
															<input type="hidden" name="tblkeyfield" 		value="<?=$tblkeyfield;?>">
															<input type="hidden" name="tblarchivedfield"	value="<?=$tblarchivedfield;?>">
															<input type="hidden" name="tbldatesortfield" 	value="<?=$tbldatesortfield;?>">
															<input type="hidden" name="tbldatesorttable" 	value="<?=$tbldatesorttable;?>">
															<input type="hidden" name="tbltextsortfield" 	value="<?=$tbltextsortfield;?>">
															<input type="hidden" name="tbltextsorttable" 	value="<?=$tbltextsorttable;?>">
															<input type="submit" value="Manage Aircraft Operations" name="B1" class="formsubmit">
															</td>
															</form>
														<form style="margin-bottom:0;" action="part121activity_main_report.php" method="POST" name="summaryform" id="summaryform" target="PrinterFriendlyWindow" onsubmit="window.open('', 'PrinterFriendlyWindow', 'width=750,height=550,status=no,resizable=no,scrollbars=yes')">
														<td class="formoptionsubmit">
															<input type="hidden" name="recordid" 			value="<?=$_POST['recordid'];?>">
															<input type="hidden" name="menuitemid" 			value="<?=$_POST['menuitemid'];?>">
															<input type="hidden" name="editpage" 			value="<?=$functioneditpage;?>">
															<input type="hidden" name="summarypage" 		value="<?=$functionsummarypage;?>">
															<input type="hidden" name="printerpage" 		value="<?=$functionprinterpage;?>">
															<input type="hidden" name="tblname" 			value="<?=$tblname;?>">
															<input type="hidden" name="tblsubname" 			value="<?=$tblsubname?>">
															<input type="hidden" name="aheadername" 		value="<?=$saheadername;?>">
															<input type="hidden" name="adatafield" 			value="<?=$sadatafield;?>">
															<input type="hidden" name="adatafieldtable" 	value="<?=$sadatafieldtable;?>">
															<input type="hidden" name="adatafieldid" 		value="<?=$sadatafieldid;?>">
															<input type="hidden" name="adataspecial" 		value="<?=$sadataspecial;?>">
															<input type="hidden" name="ainputtype" 			value="<?=$sainputtype;?>">
															<input type="hidden" name="ainputcomment" 		value="<?=$sainputcomment;?>">
															<input type="hidden" name="adataselect" 		value="<?=$sadataselect;?>">
															<input type="hidden" name="tblkeyfield" 		value="<?=$tblkeyfield;?>">
															<input type="hidden" name="tblarchivedfield"	value="<?=$tblarchivedfield;?>">
															<input type="hidden" name="tbldatesortfield" 	value="<?=$tbldatesortfield;?>">
															<input type="hidden" name="tbldatesorttable" 	value="<?=$tbldatesorttable;?>">
															<input type="hidden" name="tbltextsortfield" 	value="<?=$tbltextsortfield;?>">
															<input type="hidden" name="tbltextsorttable" 	value="<?=$tbltextsorttable;?>">
															<input type="submit" value="Printer Friendly Report" name="B1" class="formsubmit">
															</td>
															</form>
														<form style="margin-bottom:0;" action="part121activity_main_edit.php" method="POST" name="summaryform" id="summaryform">
														<td class="formoptionsubmit">
															<input type="hidden" name="recordid" 			value="<?=$_POST['recordid'];?>">
															<input type="hidden" name="menuitemid" 			value="<?=$_POST['menuitemid'];?>">
															<input type="hidden" name="editpage" 			value="<?=$functioneditpage;?>">
															<input type="hidden" name="summarypage" 		value="<?=$functionsummarypage;?>">
															<input type="hidden" name="printerpage" 		value="<?=$functionprinterpage;?>">
															<input type="hidden" name="tblname" 			value="<?=$tblname;?>">
															<input type="hidden" name="tblsubname" 			value="<?=$tblsubname?>">
															<input type="hidden" name="aheadername" 		value="<?=$saheadername;?>">
															<input type="hidden" name="adatafield" 			value="<?=$sadatafield;?>">
															<input type="hidden" name="adatafieldtable" 	value="<?=$sadatafieldtable;?>">
															<input type="hidden" name="adatafieldid" 		value="<?=$sadatafieldid;?>">
															<input type="hidden" name="adataspecial" 		value="<?=$sadataspecial;?>">
															<input type="hidden" name="ainputtype" 			value="<?=$sainputtype;?>">
															<input type="hidden" name="ainputcomment" 		value="<?=$sainputcomment;?>">
															<input type="hidden" name="adataselect" 		value="<?=$sadataselect;?>">
															<input type="hidden" name="tblkeyfield" 		value="<?=$tblkeyfield;?>">
															<input type="hidden" name="tblarchivedfield"	value="<?=$tblarchivedfield;?>">
															<input type="hidden" name="tbldatesortfield" 	value="<?=$tbldatesortfield;?>">
															<input type="hidden" name="tbldatesorttable" 	value="<?=$tbldatesorttable;?>">
															<input type="hidden" name="tbltextsortfield" 	value="<?=$tbltextsortfield;?>">
															<input type="hidden" name="tbltextsorttable" 	value="<?=$tbltextsorttable;?>">
															<input type="submit" value="Edit Record" name="B1" class="formsubmit">
															</td>
															</form>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
						<?
						while ($objarray = mysqli_fetch_array($objrs, MYSQLI_ASSOC)) {
							for ($i=0; $i<count($aheadername); $i=$i+1) {
									?>
							<tr>
								<td align="center" valign="middle" class="formoptions">
									<? 
									switch ($adataspecial[$i]) {
											case 2:
													?>
									@ <?=$aheadername[$i];?>
													<? 
													break;
											case 4:
													?>
									$ <?=$aheadername[$i];?>
													<? 
													break;
											case 5:
													?>
									<?=$aheadername[$i];?> %
													<? 
													break;
											default:
													?>
									<?=$aheadername[$i];?>
													<? 
													break;
										}
									?>
									</td>
								<td class="formanswers">
									<?
									switch ($ainputtype[$i]) {
											case "TEXT":	// if the user entered "TEXT' as the input type make a ttext area box
													$tmpvar	= $adatafield[$i];
													?>						
									<?=$objarray[$adatafield[$i]];?>
													<?
													break;
											case "TEXTAREA":	// if the user entered "TEXTAREA" as the input type make a text area box
													$tmpvar 		= str_replace("\'","'",$objarray[$adatafield[$i]] );
													?>
									<?=$objarray[$adatafield[$i]];?>
													<?	
													break;
											case "SELECT":	// if user entered "SELECT" as the input type make a select box
													// Load the specified function
													$adataselect[$i]($objarray[$adatafield[$i]], "all", $adatafield[$i], "hide", "");
													break;
											case "CHECKBOX":	// if user entered "SELECT" as the input type make a select box
														// Load the specified function
														if ($objarray[$adatafield[$i]]==0) {
																$tmpcbfield = "No";
																}
															else {
																$tmpcbfield = "Yes";
																}
																?>
										<?=$tmpcbfield;?>
																<?
														break;
											default:		// if there is an error with the user supplied input type use the 'text' type.
													?>
									<?=$objarray[$adatafield[$i]];?>
													<?
													break;
										}
										?>
									</td>
								</tr>		
									<? 
								} 
							}
							?>
							</table>
						</td>
					</tr>
				</table>
					<?
					}
				}		
		}
// END OF FILE
?>
