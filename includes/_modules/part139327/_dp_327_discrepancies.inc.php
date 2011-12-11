<?php
function _dp_327_discrepancies($dasharray) {
		//						0					1						2					3					4					5					6					7					8					9
		//$dasharray	= array($tmp_dash_main_id	,$tmp_dash_main_func	,$tmp_dash_main_nl	,$tmp_dash_main_ns	,$tmp_dash_main_p	,$tmp_dash_main_ml	,$tmp_menu_item_id	,$tmp_menu_item_loc	,$tmp_menu_item_nl	,$tmp_menu_item_ns);
		?>
<table border="1" width="290" align="left" valign="top" style="border-collapse:collapse;Margin:5px;">
	<tr>
		<td class="tableheaderleft">
			<font size='2'>
				<b>
					<?php echo $dasharray[2];?>
					</b>
				</font>
			</td>
		<td class="tableheaderright">
			<form style="margin: 0px; margin-bottom:0px; margin-top:-1px;" name="menuitem<?php echo $dasharray[6];?>" method="POST" action="<?php echo $dasharray[7];?>" target="layouttableiframecontent">
				<input type="hidden" name="menuitemid" value="<?php echo $dasharray[6];?>">
				<input class="formsubmit" type="button" name="button" value="<?php echo $dasharray[9];?>" onclick="javascript:document.menuitem<?php echo $dasharray[6];?>.submit()">
				</form>
			</td>
		</tr>
	<?php
		// Loop through active discrepancies and display a summary report for each one
		$sql 		= "SELECT * FROM tbl_139_327_sub_d 
		INNER JOIN tbl_systemusers 		ON tbl_systemusers.emp_record_id = tbl_139_327_sub_d.Discrepancy_by_cb_int 
		INNER JOIN tbl_139_327_main 	ON tbl_139_327_main.inspection_system_id = tbl_139_327_sub_d.Discrepancy_inspection_id 
		INNER JOIN tbl_139_327_sub_t 	ON tbl_139_327_sub_t.inspection_type_id = tbl_139_327_main.type_of_inspection_cb_int 
		INNER JOIN tbl_139_327_sub_c 	ON tbl_139_327_sub_c.conditions_id = tbl_139_327_sub_d.discrepancy_checklist_id  
		INNER JOIN tbl_139_327_sub_c_f 	ON tbl_139_327_sub_c_f.facility_id = tbl_139_327_sub_c.condition_facility_cb_int 
		INNER JOIN tbl_general_conditions ON tbl_general_conditions.general_condition_id = tbl_139_327_sub_d.discrepancy_priority ";
		
		//echo $sql;
		$objconn 	= mysqli_connect($GLOBALS['hostdomain'], $GLOBALS['hostusername'], $GLOBALS['passwordofdatabase'], $GLOBALS['nameofdatabase']);

		if (mysqli_connect_errno()) {
				printf("connect failed: %s\n", mysqli_connect_error());
				exit();
			}
			else {
				$objrs = mysqli_query($objconn, $sql);		
				if ($objrs) {
						$number_of_rows 	= mysqli_num_rows($objrs);
						while ($objarray 	= mysqli_fetch_array($objrs, MYSQLI_ASSOC)) {
						
								$displayrow					= 0;
								$displayrow_a				= 0;
								$displayrow_b				= 0;
	
								$tmpdiscrepancyid			= $objarray['Discrepancy_id'];
								$tmpdiscrepancycondition	= $objarray['discrepancy_checklist_id'];	
								
								$displayrow_a				= preflights_tbl_139_327_main_sub_d_a_yn($tmpdiscrepancyid,0); // 0 will not return a row even if it is archieved.
								$displayrow_d				= preflights_tbl_139_327_main_sub_d_d_yn($tmpdiscrepancyid,0); // 0 will not return a row even if it is duplicate.

								if($displayrow_a == 0 OR $displayrow_d == 0) {
										// display nothing
										$displayrow = 0;
									}
									else {
										// Check Status of this Discrepancy, ie. Get the current stage
										
										$status = part139327discrepancy_getstage($tmpdiscrepancyid,0, 0,0,0);
										
										if($status == 0) {
												// Display Summary Report
												?>
	<tr>
		<td colspan="2">
		<?php
												display_discrepancy_summary($tmpdiscrepancyid,0,0);
												?>
			</td>
		</tr>
	<tr>
		<td colspan="2" class='formoptions' align="right">
			<?php
			// Load Workorder Controls
			// Lie to the blockform
			$disid					= $tmpdiscrepancyid;
			$imclearlyahijacker		= 1;
			$functionworkorderpage 	= 1;
			$functionworkorderpage	= 'part139327_discrepancy_report_display_workorder.php';
			$functionrepairpage		= 'part139327_discrepancy_report_repair.php';
			$functionbouncepage		= 'part139327_discrepancy_report_bounce.php';
			$array_repairedcontrol	= array(0,0,'part139327_discrepancy_report_display_repaired.php');
			$array_bouncedcontrol	= array(0,0,'part139327_discrepancy_report_display_bounced.php');
			// Utilize our lies
			?>
			<table border="0" cellpadding='0' cellspacing='0' style="border: collapse;" align='right'>
				<tr>
			<?php
			include("includes/_template/_tp_blockform_workorder.binc.php");	
			?>
					</tr>
				</table>
			</td>	
		</tr>
												<?php
											}
									}	
							}
					}
			}
		
		
		
		?>
			</td>
		</tr>
	</table>
	<?php
	}
?>