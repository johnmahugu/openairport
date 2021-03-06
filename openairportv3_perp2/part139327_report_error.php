<?php 
//		  1		    2		  3		    4		  5		    6		  7		    7	      8		
//2345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345
//==============================================================================================
//	
//	ooooo	oooo	ooooo	o		o	ooooo	ooooo	oooo	oooo	ooooo	oooo	ooooo
//	o   o	o	o	o		oo		o	o	o	  o		o	o	o	o	o	o	o	o	  o
//	o	o	o	o	o		o o		o	o	o	  o		o	o	o	o	o	o	o	o	  o
//	o	o	oooo	oooo	o 	o	o	ooooo	  o		oooo	oooo	o	o	oooo	  o	
//	o	o	o		o		o  	 o	o	o	o	  o		o  o	o		o	o	o  o	  o
//	o	o	o		o		o	  o	o	o	o	  o		o	o	o		o	o	o   o	  o
//	00000	0		ooooo	o		o	o	o	ooooo	o	o	o		ooooo	o	o     o
//
//	The premium quality open source software soultion for airport record keeping requirements
//
//	Designed, Coded, and Supported by Erick Dahl
//
//	Copywrite 2002 - Whatever the current year is
//
//	~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~
//	
//	Name of Document		:	part139327_report_error.php
//
//	Purpose of Page			:	Enter new Part 139.327 Inspection Error Report
//
//	Special Notes			:	Change the information here for your airport.
//
//==============================================================================================
//2345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345
//		  1		    2		  3		    4		  5		    6		  7		    7	      8	

// Load Global Include Files
	
		include("includes/_template_header.php");												// This include 'header.php' is the main include file which has the page layout, css, and functions all defined.
		include("includes/POSTs.php");															// This include pulls information from the $_POST['']; variable array for use on this page

// Load Page Specific Includes

		include("includes/_modules/part139327/part139327.list.php");
		include("includes/_template_enter.php");
		include("includes/_template/template.list.php");

// Define Variables	
		
		$navigation_page 			= 16;							// Belongs to this Nav Item ID, see function for notes!
		$type_page 					= 5;							// Page is Type ID, see function for notes!
		$date_to_display_new		= AmerDate2SqlDateTime(date('m/d/Y'));
		$time_to_display_new		= date("H:i:s");

// Build the BreadCrum trail which shows the user their current location and how to navigate to other sections.
	
		//buildbreadcrumtrail($strmenuitemid,$frmstartdate,$frmenddate);
	
// Start Procedures	

if (!isset($_POST["formsubmit"])) {

// This is a FUNCTION LOADED FROM THE TEMPLATE BROWSER
//		Anytime a window is openned from the template browser the following should be loaded into the FORM
//----------------------------------------------------------------------------------------------\\
		//	$bstart_date 	= $_GET['startdate'];												// The 'TB' Start Date 	(nonSQL)
		//	$bend_date 		= $_GET['enddate'];													// The 'TB' End Date 	(nonSQL)

//	Start Form Set Variables
	
	// FORM HEADER
	// -----------------------------------------------------------------------------------------\\
			$formname			= "edittable";													// HTML Name for Form
			$formaction			= "";															// Page Form will submit information to. Leave valued at '' for the form to point to itself.
			$formopen			= 0;															// 1: Opens action page in new window, 0, submits to same window
				$formtarget		= "";															// HTML Name for the window
				$location		= $formtarget;													// Leave the same as $formtarget
	
	// FORM NAME and Sub Title
	//------------------------------------------------------------------------------------------\\
			$form_menu			= "Mark Inspection witn an Error";								// Name of the FORM, shown to the user
			$form_subh			= "Please complete the form";									// Sub Name of the FORM, shown to the user
			$subtitle 			= "Use this form to mark the inspection with an error";			// Subt title of the FORM, shown to the user

	// FORM SUMMARY information
	//------------------------------------------------------------------------------------------\\
			$displaysummaryfunction 	= 1;													// 1: Display Summary of Record, 0: Do not show summary
				$summaryfunctionname 	= '_327_display_report_summary';						// Function to display the summary, leave as '' if not using the summary function
				$idtosearch				= $_POST['recordid'];									// ID to look for in the summary function, this is typically $_POST['recordid'].
				$detailtodisplay		= 0;													// See Summary Function for how to use this number
				$returnHTML				= 0;													// 1: Returns only an HTML variable, 0: Prints the information as assembled.
					
		include("includes/_template/_tp_blockform_form_header_inline.binc.php");

	// FORM ELEMENTS
	//-----------------------------------------------------------------------------------------\\	
	//
	//				Field Name			Field Text Name				Field Comment						Field Notes												Field Format		Field Type	Field Width		Field Height	Default Value			Field Function		
	form_new_table_b_inline($formname);
	form_new_control("disdate"		,"Date"			, "Enter the date this inspection was marked as a error"		,"The current date has automatically been provided!"			,"(mm/dd/yyyy)"			,1,10,0,"current",0);
	form_new_control("distime"		,"Time"			, "Enter the time this inspection was marked as a error"		,"The current time has automatically been provided!"			,"(hh:mm:ss) - 24 hours",1,10,0,"current",0);
	form_new_control("disauthor"	,"Entry By"		, "Who found and reported this inspection"						,"Your name has automatically been provided!"					,"(cannot be changed)"	,3,50,0,$_SESSION['user_id'],"systemusercombobox");
	form_new_control("discomments"	,"Comments"		, "Provide information about an error made"						,"Do not use any special characters!"							,""						,2,20,4,"",0);
	form_new_control("disduplicate"	,"Mark Error"	, "Checking this box will mark the inspection with an error"	,"Only do this if you are sure the inspection is a duplicate"	,"(checked = error)"	,5,35,4,"current",0);
	form_new_table_e($formname); 
			
	// FORM UNIVERSAL CONTROL LOADING
	//------------------------------------------------------------------------------------------\\
	
	$targetname		= $_POST['targetname'];			// From the Button Loader; Name of the window this form was loaded into.
	$dhtml_name		= $_POST['dhtmlname'];			// From the Button Loader; Name of the DHTML window function to call to change this window.
	form_uni_control("targetname"		,$targetname);
	form_uni_control("dhtmlname"		,$dhtml_name);
	
	//
	// FORM FOOTER
	//------------------------------------------------------------------------------------------\\
			$display_submit 		= 1;														// 1: Display Submit Button,	0: No
				$submitbuttonname	= 'Save Error Report';										// Name of the Submit Button
			$display_close			= 1;														// 1: Display Close Button, 	0: No
			$display_pushdown		= 0;														// 1: Display Push Down Button, 0: No
			$display_refresh		= 0;														// 1: Display Refresh Button, 	0: No
			$display_quickaccess	= 0;
			
		include("includes/_template/_tp_blockform_form_footer_inline.binc.php");
	
	}
	else {
	
	// FORM HEADER
	// -----------------------------------------------------------------------------------------\\
			$formname			= "edittable";													// HTML Name for Form
			$formaction			= '';															// Page Form will submit information to. Leave valued at '' for the form to point to itself.
			$formopen			= 0;															// 1: Opens action page in new window, 0, submits to same window
				$formtarget		= '';															// HTML Name for the window
				$location		= $formtarget;													// Leave the same as $formtarget
	
	// FORM NAME and Sub Title
	//------------------------------------------------------------------------------------------\\
			$form_menu			= "Mark Inspection Error - Summary Report";						// Name of the FORM, shown to the user
			$form_subh			= "Here is the information you entered";						// Sub Name of the FORM, shown to the user
			$subtitle 			= "Here is the information about the selected error Report";	// Subt title of the FORM, shown to the user

	// FORM SUMMARY information
	//------------------------------------------------------------------------------------------\\
			$displaysummaryfunction 	= 0;													// 1: Display Summary of Record, 0: Do not show summary
				$summaryfunctionname 	= '';													// Function to display the summary, leave as '' if not using the summary function
				$idtosearch				= '';													// ID to look for in the summary function, this is typically $_POST['recordid'].
				$detailtodisplay		= 0;													// See Summary Function for how to use this number
				$returnHTML				= 0;													// 1: Returns only an HTML variable, 0: Prints the information as assembled.
					
		include("includes/_template/_tp_blockform_form_header.binc.php");				
	
	// Load Form Elements	
	// Place Default values from the POST Here or enter 'post'-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------\
	//																																																																												|
	//		Put a '0' here if you do not want to display the form field and only the result-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------\								|
	//																																																																				 v								v		
	form_new_table_b_inline($formname);
	form_new_control("disdate"		,"Date"			, "Enter the date this inspection was marked as a error"		,"The current date has automatically been provided!"			,"(mm/dd/yyyy)"			,1,0,0,'post',0);
	form_new_control("distime"		,"Time"			, "Enter the time this inspection was marked as a error"		,"The current time has automatically been provided!"			,"(hh:mm:ss) - 24 hours",1,0,0,'post',0);
	form_new_control("disauthor"	,"Entry By"		, "Who found and reported this inspection"						,"Your name has automatically been provided!"					,"(cannot be changed)"	,3,0,0,'post',"systemusercombobox");
	form_new_control("discomments"	,"Comments"		, "Provide information about an error made"						,"Do not use any special characters!"							,""						,2,0,4,'post',0);
	form_new_control("disduplicate"	,"Mark Error"	, "Checking this box will mark the inspection with an error"	,"Only do this if you are sure the inspection is a duplicate"	,"(checked = error)"	,5,0,4,'post',0);

	// FORM UNIVERSAL CONTROL LOADING
	//------------------------------------------------------------------------------------------\\
	
	$targetname		= $_POST['targetname'];			// From the Button Loader; Name of the window this form was loaded into.
	$dhtml_name		= $_POST['dhtmlname'];			// From the Button Loader; Name of the DHTML window function to call to change this window.
	form_uni_control("targetname"		,$targetname);
	form_uni_control("dhtmlname"		,$dhtml_name);
	
	//
	// FORM FOOTER
	//------------------------------------------------------------------------------------------\\
			$display_submit 		= 0;														// 1: Display Submit Button,	0: No
				$submitbuttonname	= '';														// Name of the Submit Button
			$display_close			= 1;														// 1: Display Close Button, 	0: No
			$display_pushdown		= 0;														// 1: Display Push Down Button, 0: No
			$display_refresh		= 0;														// 1: Display Refresh Button, 	0: No
			$display_quickaccess	= 0;
			
		include("includes/_template/_tp_blockform_form_footer.binc.php");		
	
	// DO SQL Work			
	
		//$sqldate		= AmerDate2SqlDateTime($_POST['disdate']);
		$sqldate		=($_POST['disdate']);
		
		$sql = "INSERT INTO tbl_139_327_main_e (inspection_error_inspection_id, inspection_error_by_cb_int, inspection_error_reason, inspection_error_date, inspection_error_time, inspection_error_yn)
		VALUES ( '".$_POST['recordid']."', '".$_POST['disauthor']."', '".$_POST['discomments']."', '".$sqldate."', '".$_POST['distime']."', '".$_POST['disduplicate']."' )";
		
		$mysqli = mysqli_connect($GLOBALS['hostdomain'], $GLOBALS['hostusername'], $GLOBALS['passwordofdatabase'], $GLOBALS['nameofdatabase']);
			//mysql_insert_id();
				
				if (mysqli_connect_errno()) {
						// there was an error trying to connect to the mysql database
						printf("connect failed: %s\n", mysqli_connect_error());
						exit();
					}		
					else {
					//mysql_insert_id();
						$objrs = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
						$lastid = mysqli_insert_id($mysqli);
						}

	}
		
// Establish Page Variables
		if (!isset($lastid)) {
				// Not defined, set to zero
				$last_main_id = 0;
			} else {
				$last_main_id = $lastid;
			}		
		if (!isset($_POST["formsubmit"])) {
				// Not defined, set to zero
				$submit = 0;
			} else {
				$submit = $_POST["formsubmit"];
			}

		$auto_array		= array($navigation_page, $_SESSION["user_id"], $submit, $date_to_display_new, $time_to_display_new, $type_page,$last_main_id); 
		ae_completepackage($auto_array);
	
// Load End of page includes
//	This page closes the HTML tag, nothing can come after it.

		include("includes/_userinterface/_ui_footer.inc.php");							// Include file providing for Tool Tips			
?>