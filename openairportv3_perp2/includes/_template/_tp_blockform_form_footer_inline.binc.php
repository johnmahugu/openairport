<?php 
//		  1		    2		  3		    4		  5		    6		  7		    7	      8		
//2345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345
//==============================================================================================
//	
//	ooooo	oooo	ooooo	o	o		ooooo	ooooo	oooo	oooo	ooooo	oooo	ooooo
//	o   o	o	o	o		o	o		o	o	  o		o	o	o	o	o	o	o	o	  o
//	o	o	o	o	o		oo	o		o	o	  o		o	o	o	o	o	o	o	o	  o
//	o	o	oooo	oooo	o o	o		ooooo	  o		oooo	oooo	o	o	oooo	  o	
//	o	o	o		o		o  oo		o	o	  o		o  o	o		o	o	o  o	  o
//	o	o	o		o		o	o		o	o	  o		o	o	o		o	o	o	o     o
//	00000	0		ooooo	o	o		o	o	ooooo	o	o	o		ooooo	o	o     o
//
//	The premium quality open source software soultion for airport record keeping requirements
//
//	Designed, Coded, and Supported by Erick Dahl
//
//	Copywrite 2002 - Whatever the current year is
//
//	~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~
//	
//	Name of Document	:	_tp_blockform_form_footer.binc
//
//	Purpose of Page		:	The Standard Programming Block takes parts of the main code and
//							seperates them across a few different include files for the purpose
//							of cleaning up each main code page and using reunable code.
//
//	Special Notes		:	Under normal conditions there should be no need to change this page
//							In the event you wish to change this page, everything should be
//							rather stright forward in what it does and how to change it.
//
//==============================================================================================
//2345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345
//		  1		    2		  3		    4		  5		    6		  7		    7	      8		

?>
<tr>
	<td class="item_name_active" height='12' colspan='6' style='max-height:12px;' />
		<?php
		if($display_refresh == 1) {
				_tp_control_footbuttons(2,"sorttable");
			}	
		if($display_pushdown == 1) {
				_tp_control_footbuttons(3,$pushdown_frmname,$pushdown_otherid,$pushdown_script);
			}									
		if($display_submit == 1) {
				_tp_control_footbuttons(4,$formname,$submitbuttonname);
			}
			
		if (!isset($display_quickaccess)) {	
				// Not set
			} else {
				if($display_quickaccess == 1) {
						_tp_control_footbuttons(5,$formname,$strmenuitemid);
					}
			}
			
		if (!isset($display_printout)) {	
				// Not set
			} else {
				if($display_printout == 1) {
						_tp_control_footbuttons(6,$printout_page,$printout_id,$printout_passed);
					}
			}
		if($display_close == 1) {
				_tp_control_footbuttons(1,$formname,$dhtml_name);
			}	
		?>
		</td>
	</tr>
<?php
form_new_table_container('close');
?>