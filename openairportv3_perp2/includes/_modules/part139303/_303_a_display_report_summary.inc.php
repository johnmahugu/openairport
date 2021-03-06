<?php
function _303_a_display_report_summary($discrepancyid = 0,$detail_level = 0,$returnhtml = 0) {
		// Display Discrepancy Summary
		// Detail Level:
		//					0 : Name and Remark
		//					1 : All Local Table Values
		//					2 : Everything!!!! (1,3,4,5,6,7,8,etc...)
		//					3 : 1 + Archieved
		//					4 : 1 + Error
		
		$display_basic 		= 0;
		$display_extended 	= 0;
		$display_archived	= 0;
		$display_error		= 0;
		
		$basicHTML			= '';
		$extendedHTML		= '';
		$archievedHTML_i	= '';
		$archivedHTML		= '';
		$errorHTML_i		= '';
		$errorHTML			= '';

		$webroot			= "http://localhost/openairportv3_lcars/";
		
		if($detail_level == 0) {
				$display_basic 		= 1;
		
			}
		if($detail_level == 1) {
				$display_basic 		= 1;
				$display_extended 	= 1;

			}			
		if($detail_level == 2) {
				$display_basic 		= 1;
				$display_extended 	= 1;
				$display_archived	= 1;
				$display_error		= 1;
		
			}
		if($detail_level == 3) {
				$display_basic 		= 1;
				$display_extended 	= 1;
				$display_archived	= 1;
		
			}			
		if($detail_level == 4) {
				$display_basic 		= 1;
				$display_extended 	= 1;
				$display_error		= 1;
		
			}			
			
		$sql 	= "SELECT * FROM tbl_systemusers 
					INNER JOIN tbl_organization_main ON tbl_organization_main.Organizations_id = tbl_systemusers.emp_organiation_cb_int ";
		
		if($discrepancyid == 0) {
				// No discrepancy is defined, find all
			}
			else {
				// Use the specific entry
				$sql = $sql."WHERE emp_record_id = '".$discrepancyid."' ";
			}
		
		//echo $sql;
		$objconn 	= mysqli_connect($GLOBALS['hostdomain'], $GLOBALS['hostusername'], $GLOBALS['passwordofdatabase'], $GLOBALS['nameofdatabase']);
		
		if($returnhtml == 0) {
				// Just display the results now
				echo "<table width='100%' cellpadding='1' cellspacing='1' border='0' align='left' valign='top' style='float:left;' />";
			}
			else {
				// DO NOT display anything YET!!!!!
				$table_i = "<table width='100%' cellpadding='1' cellspacing='1' border='0' align='left' valign='top' style='float:left;' />";
			}

		
		if (mysqli_connect_errno()) {
				printf("connect failed: %s\n", mysqli_connect_error());
				exit();
			}
			else {
				$objrs = mysqli_query($objconn, $sql);		
				if ($objrs) {
						$number_of_rows 	= mysqli_num_rows($objrs);
						while ($objarray 	= mysqli_fetch_array($objrs, MYSQLI_ASSOC)) {
						
								$discrepancyid = $objarray['emp_record_id'];
								
								$basicHTML = "
												<tr>		
													<td align='center' valign='middle' class='table_dashpanel_container_summary_rowheader'>
														ID
														</td>
													<td class='table_dashpanel_container_summary_rowresult'>
															<a onclick='openmapchild(&quot;part139303_a_report_display.php?recordid=".$discrepancyid."&quot;,&quot;SummaryReportWindow&quot;)'; />".$discrepancyid."</a>
														</td>
													</tr>
												<tr>		
													<td align='center' valign='middle' class='table_dashpanel_container_summary_rowheader'>
														First / Last  (initials)
														</td>
													<td class='table_dashpanel_container_summary_rowresult'>".
														$objarray['emp_firstname']." / ".$objarray['emp_lastname']." (".$objarray['emp_initials'].") 
														</td>
													</tr>
												<tr>		
													<td align='center' valign='middle' class='table_dashpanel_container_summary_rowheader'>
														Organizations
														</td>
													<td class='table_dashpanel_container_summary_rowresult'>".
														$objarray['org_name']." 
														</td>
													</tr>													
													";

								if($returnhtml == 0) {
										// Just display the results now
										echo $basicHTML;
									}
									else {
										// DO NOT display anything YET!!!!!
									}

								if($display_extended == 1) {
							
										$extendedHTML = "
														<tr>
															<td colspan='2' class='table_dashpanel_container_summary_header'>
																Extended Information
																</td>
															</tr>									
														<tr>		
															<td align='center' valign='middle' class='table_dashpanel_container_summary_rowheader'>
																Username
																</td>
															<td class='table_dashpanel_container_summary_rowresult'>".
																$objarray['emp_username']."
																</td>
															</tr>
														<tr>		
															<td align='center' valign='middle' class='table_dashpanel_container_summary_rowheader'>
																Password
																</td>
															<td class='table_dashpanel_container_summary_rowresult'>
																******************
																</td>
															</tr>
														<tr>		
															<td align='center' valign='middle' class='table_dashpanel_container_summary_rowheader'>
																Access level
																</td>
															<td class='table_dashpanel_container_summary_rowresult'>".
																_nav_getbysuid_navigationalgrouptext($discrepancyid)."
																</td>
															</tr>	
														<tr>		
															<td align='center' valign='middle' class='table_dashpanel_container_summary_rowheader'>
																Added on Date:
																</td>
															<td class='table_dashpanel_container_summary_rowresult'>".
																$objarray['emp_addedon_date']."
																</td>
															</tr>	
														<tr>		
															<td align='center' valign='middle' class='table_dashpanel_container_summary_rowheader'>
																Added on Time:
																</td>
															<td class='table_dashpanel_container_summary_rowresult'>".
																$objarray['emp_addon_time']."
																</td>
															</tr>
														<tr>		
															<td align='center' valign='middle' class='table_dashpanel_container_summary_rowheader'>
																Added on By:
																</td>
															<td class='table_dashpanel_container_summary_rowresult'>".
																systemusertextfield($objarray['emp_added_by_int'], "all", "any", "hide", $objarray['emp_added_by_int'])."
																</td>
															</tr>															
															";

										if($returnhtml == 0) {
												// Just display the results now
												echo $extendedHTML;
											}
											else {
												// DO NOT display anything YET!!!!!
											}								
									}
								
								if($display_archived == 1) {	

										// Display all Bounced Information
										$sql2 = "SELECT * FROM tbl_139_303_a_main_a 
										INNER JOIN tbl_systemusers ON tbl_systemusers.emp_record_id = tbl_139_303_a_main_a.139303_a_a_by_cb_int   
										WHERE 139303_a_a_inspection_id = '".$discrepancyid."' 
										ORDER BY 139303_a_a_date,139303_a_a_time";
										
										//echo $sql2;
										
										$objconn2 = mysqli_connect($GLOBALS['hostdomain'], $GLOBALS['hostusername'], $GLOBALS['passwordofdatabase'], $GLOBALS['nameofdatabase']);
										if (mysqli_connect_errno()) {
												// there was an error trying to connect to the mysql database
												printf("connect failed: %s\n", mysqli_connect_error());
												exit();
											}
											else {
												$objrs2 = mysqli_query($objconn2, $sql2);
												if ($objrs2) {
														$number_of_rows = mysqli_num_rows($objrs2);
														if($number_of_rows >=1) {
																
																$archievedHTML_i = "
																				<tr>
																					<td colspan='2' class='table_dashpanel_container_summary_header'>
																						Archived Information
																						</td>
																					</tr>
																				";
																				
																if($returnhtml == 0) {
																		// Just display the results now
																		echo $archievedHTML_i;
																	}
																	else {
																		// DO NOT display anything YET!!!!!
																	}		
															}
															else {
															
																$archievedHTML_i = "
																				<tr>
																					<td colspan='2' class='table_dashpanel_container_summary_header'>
																						Archived Information
																						</td>
																					</tr>
																				<tr>
																					<td colspan='2' class='table_dashpanel_container_summary_rowresult'>
																						There are no records to display
																						</td>
																					</tr>
																				";
																				
																if($returnhtml == 0) {
																		// Just display the results now
																		echo $archievedHTML_i;
																	}
																	else {
																		// DO NOT display anything YET!!!!!
																	}																
															}
															
														while ($objarray2 = mysqli_fetch_array($objrs2, MYSQLI_ASSOC)) {	
														
																$archivedHTML = $archivedHTML."
																				<tr>		
																					<td align='center' valign='middle' class='table_dashpanel_container_summary_rowheader'>
																						Date / Time
																						</td>
																					<td class='table_dashpanel_container_summary_rowresult'>".
																						$objarray2['139303_a_a_date']." / ".$objarray2['139303_a_a_time']." 
																						</td>
																					</tr>
																				<tr>		
																					<td align='center' valign='middle' class='table_dashpanel_container_summary_rowheader'>
																						Archieved By
																						</td>
																					<td class='table_dashpanel_container_summary_rowresult'>".
																						$objarray2['emp_firstname']." ".$objarray2['emp_lastname']." 
																						</td>
																					</tr>
																				<tr>		
																					<td align='center' valign='middle' class='table_dashpanel_container_summary_rowheader'>
																						Comments
																						</td>
																					<td class='table_dashpanel_container_summary_rowresult'>".
																						$objarray2['139303_a_a_reason']." 
																						</td>
																					</tr>
																				";
															}
													}
											}
										if($returnhtml == 0) {
												// Just display the results now
												echo $archivedHTML;
											}
											else {
												// DO NOT display anything YET!!!!!
											}
									}		
									
								if($display_error == 1) {	

										// Display all Bounced Information
										$sql2 = "SELECT * FROM tbl_139_303_a_main_e 
										INNER JOIN tbl_systemusers ON tbl_systemusers.emp_record_id = tbl_139_303_a_main_e.139303_a_e_by_cb_int  
										WHERE 139303_a_e_inspection_id = '".$discrepancyid."' 
										ORDER BY 139303_a_e_date,1393903_a_e_time";
										
										$objconn2 = mysqli_connect($GLOBALS['hostdomain'], $GLOBALS['hostusername'], $GLOBALS['passwordofdatabase'], $GLOBALS['nameofdatabase']);
										if (mysqli_connect_errno()) {
												// there was an error trying to connect to the mysql database
												printf("connect failed: %s\n", mysqli_connect_error());
												exit();
											}
											else {
												$objrs2 = mysqli_query($objconn2, $sql2);
												if ($objrs2) {
														$number_of_rows = mysqli_num_rows($objrs2);
														if($number_of_rows >=1) {
																
																$errorHTML_i = "
																				<tr>
																					<td colspan='2' class='table_dashpanel_container_summary_header'>
																						Error Information
																						</td>
																					</tr>
																				";
																				
																if($returnhtml == 0) {
																		// Just display the results now
																		echo $errorHTML_i;
																	}
																	else {
																		// DO NOT display anything YET!!!!!
																	}		
															}
															else {
															
																$errorHTML_i = "
																				<tr>
																					<td colspan='2' class='table_dashpanel_container_summary_header'>
																						Error Information
																						</td>
																					</tr>
																				<tr>
																					<td colspan='2' class='table_dashpanel_container_summary_rowresult'>
																						There are no records to display
																						</td>
																					</tr>
																				";
																				
																if($returnhtml == 0) {
																		// Just display the results now
																		echo $errorHTML_i;
																	}
																	else {
																		// DO NOT display anything YET!!!!!
																	}																
															}
															
														while ($objarray2 = mysqli_fetch_array($objrs2, MYSQLI_ASSOC)) {	
														
																$archivedHTML = $errorHTML."
																				<tr>		
																					<td align='center' valign='middle' class='table_dashpanel_container_summary_rowheader'>
																						Date / Time
																						</td>
																					<td class='table_dashpanel_container_summary_rowresult'>".
																						$objarray2['139303_a_e_date']." / ".$objarray2['139303_a_e_time']." 
																						</td>
																					</tr>
																				<tr>		
																					<td align='center' valign='middle' class='table_dashpanel_container_summary_rowheader'>
																						Archieved By
																						</td>
																					<td class='table_dashpanel_container_summary_rowresult'>".
																						$objarray2['emp_firstname']." ".$objarray2['emp_lastname']." 
																						</td>
																					</tr>
																				<tr>		
																					<td align='center' valign='middle' class='table_dashpanel_container_summary_rowheader'>
																						Comments
																						</td>
																					<td class='table_dashpanel_container_summary_rowresult'>".
																						$objarray2['139303_a_e_reason']." 
																						</td>
																					</tr>
																				";
															}
													}
											}
										if($returnhtml == 0) {
												// Just display the results now
												echo $errorHTML;
											}
											else {
												// DO NOT display anything YET!!!!!
											}
									}
									
							}
					}
			}
			
		if($returnhtml == 0) {
				// Just display the results now
				echo "</table>";
			}
			else {
				// DO NOT display anything YET!!!!!
				$table_o = "</table>";
			}			
			
		if($returnhtml == 0) {
				// Just display the results now
				// Display Nothing
			}
			else {
				// Assemble a return variable
				$return_string = $table_i."".$basicHTML."".$extendedHTML."".$archievedHTML_i."".$archievedHTML."".$errorHTML_i."".$errorHTML."".$table_o."";
				return $return_string;
			}			
	}
	?>