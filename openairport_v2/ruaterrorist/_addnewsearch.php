<?
//		 1		   2		 3		   4		 5		   6		 7		   8	     9		   
//3456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789
//==============================================================================================
//	
//	oooo	o   o	 ooo	ooooo
//	o	o	o	o	o	o	  o
//	o	o	o	o	o	o	  o
//	oooo	o   o	ooooo	  o	
//	o  o	o	o	o	o	  o
//	o	o	o	o	o	o	  o
//	o	o	ooooo	o   o	  o
//
//	The "Are You a Terrorist?" (RUAT) system.
//
//	Designed, Coded, and Supported by Erick Alan Dahl
//
//	Copywrite 2008 - Erick Alan Dahl
//
//	~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~
//	
//	Name of Document	:	_addnewsearch.php
//
//	Purpose of Page		:	FORM SIDE 	- Allows User to Create and Save Searchs
//							SERVER SIDE - Takes the users input and searchs the database for any matchs
//
//==============================================================================================
//3456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789
//		 1		   2		 3		   4		 5		   6		 7		   8	     9	

// Prevent System From Timeing out while doing a search of the database	   
		set_time_limit(0);
		
// Include Files nessary to complete any tasks assigned.		
		include("includes/generalsettings.php");
		include("includes/functions.php");
		include("includes/interface.php");
		
// Establish Page Name			
		$pagename = 'Add New Search Query(ies)';
		
// Check to see if a user is currently logged Into the System.  We do this to prevent direct linking
//	to the document. If someone tries to direct link to the page, the broweser will show a 404 error.
		//echo "Session ID is [".$_SESSION['user_id'],"]";
		if ($_SESSION['user_id'] == '') {
				//echo "There has been a request for this page outside of normal operating procedures<br>";
				send_404();
			}
			else {
				//echo "The request for this page seems to be in order, allow page to be displayed <br>";
				?>
<HTML>
	<HEAD>
		<TITLE>
			Transportation Security Administration SSI - Manage Search Queries
			</TITLE>
		<link rel="stylesheet" href="scripts/style.css" type="text/css">
		</HEAD>
	<BODY >
		<?
		// This Div layer will show a precent completed of the current search query. It relates directly 
		//	to the procedures that follow the While loop.
		?>
		<div id="myDiv" style="display:none; position:absolute; z-index:9; left:0; top:0; width:302;">
			<table width="100%" Class="windowMain" CELLSPACING=1 CELLPADDING=2>
				<tr>
					<td class="newTopicTitle">Search Query Progress</td>
					</tr>
				<tr>
					<td class="newTopicBoxes">
						<div id="myDiv_bar" style="display:none; width:0; background-color: #7198BF;background-image:url('images/animated_timer_bar.gif'); background-repeat:no-repeat;background-position: left center;">
							<table>
								<tr>
									<td class="newTopicTitle" id="percent" align="center" valign="middle">
										&nbsp;
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>				
				<tr>
					<td colspan="2" class="newTopicEnder">&nbsp;</td>
					</tr>
				</table>
			</div>
		<table width="100%" Class="windowMain" CELLSPACING=1 CELLPADDING=2>
<?
$time_b 	= timer();		
if ($_POST['submitform']==1) {
		?>
			<tr>
				<td colspan="13">
					<?
					breadcrumbs('_addnewsearch.php', 'Here are your query results');
					put_systemactivity($_SESSION["user_id"],'Results from Add New Search Query are displayed');
					?>
					</td>
				</tr>
			<tr>
				<td colspan="13" class="newTopicTitle">
					Here are your search query results
					</td>
				</tr>
			<tr>
				<td colspan="13" class="newTopicBoxes">
					<p>
						Listed here are is a comprehensive list of all people on a watch list that 
						meet the critera you set in your search. Please look over the list carefully
						to see if there is a match. Depending on the type of query you ran, there may 
						people on this list that look like matches but are listed because they share 
						a common part of their name or pass some other logical test.
						</p>
					</td>
				</tr>
		<?
		// Looks like the user has submited the search for. Now the system has to develope a way to 
		//	search the watch lists and come up with some results. This is a multi-step process and 
		//	at times it is a little trying.
		
		// STEP ONE: 
				//	We need the name of the file the user uploaded to compare the watch lists against. 
				//	Pretty basec SQL Statement.		

						$sql = "SELECT * FROM tbl_users_files WHERE user_f_parent_id = '".$_SESSION["user_id"]."' LIMIT 1";
						$objconn_support = mysqli_connect("localhost", "webuser", "limitaces", "tsa_ruat");
						if (mysqli_connect_errno()) {
								printf("connect failed: %s\n", mysqli_connect_error());
								exit();
							}
							else {
								$objrs_support = mysqli_query($objconn_support, $sql);
								if ($objrs_support) {
										$number_of_rows = mysqli_num_rows($objrs_support);
										if ($number_of_rows == 0) {
												?>
												Sorry, you do not have a file on record to compare the lists to.
												<?
											}
										while ($objfields = mysqli_fetch_array($objrs_support, MYSQLI_ASSOC)) {
												$filename = $objfields['user_f_name'];
												//echo "File is ".$filename."<br>";
											}
									}
									mysqli_free_result($objrs_support);
									mysqli_close($objconn_support);
							}
					
				// 	Now tat we have the name of the users CSV file. We need to do some caclulations 
				//	to determine the number of rows in the file and establish a connection to the file.
		
						$fcontents 			= file ($filename); 									// fcontents is the file object
						$tmp_sizeoffile		= sizeof($fcontents);									// tmp_sizeoffile is the total number of rows in the file
						$known_sids 		= 0;													// Depreciated, on path to deletion.
						$counter			= 0;													// Depreciated, on path to deletion.
						$totalrowsprocessed = 0;													// Depreciated, on path to deletion.
						$nsql				= '';													// Used to assemble the SQL Statement. Listed here to remind us it is a string.
						//echo "The User's CSV file has ".$tmp_sizeoffile." rows <br>";
						//echo "The Counter has a value of [".$counter."] <br>";
						//echo "The NSQL has a value of [".$nsql."] <br>";

		// STEP TWO: 
				//	With Step One completed, we now need to set some variables based on the type of 
				//	watch list the user has selected to compare their list to.						
						
						//$tmp_liststocheck = $_POST['group1'];										// Radio Group for The Watch list Selection
						//$tmp_typeoflisttc = $_POST['group4'];										// Radio Group for The Watch list Selection
						//echo "Group 4 is ".$tmp_typeoflisttc."<br>";
						//echo "Group 1 is ".$tmp_liststocheck." <br>";
		
						
						$scobby				= 3;
						$array_tables[0] 	= 'tbl_nofly_add_list';
						$array_tables[1] 	= 'tbl_selectee_add_list';
						$array_tables[2] 	= 'tbl_cleared_add_list';
						$a					= getnumberoflistrows($array_tables[0]);
						$b					= getnumberoflistrows($array_tables[1]);
						$c					= getnumberoflistrows($array_tables[2]);
						$totalsize			= ($a + $b + $c);
						//echo "Total Size ".$totalsize."<br>";		
						//echo "a".$a." b".$b." c".$c."<br>";
							
				//	Watch list values have now been set. Now setting the total number of rows we can 
				//	expect to have to search through reguardless if we find a match or not.
		
						$totalexpectedcycles = ($tmp_sizeoffile * $totalsize); 						// Math says that given a certain number of people in list A, and a certain number in list a,b,c or all, the product of the two lists will give the number of rows to search.
						//echo "Size of file ".$tmp_sizeoffile."<br>";
						//echo "total size ".$totalsize."<br>";
						//echo "Total Cycles ".$totalexpectedcycles."<br>";
						
				// Display some debugging code if needed.
						//echo "Table Array [0] ".$array_tables[0]."<br>";
						//echo "Table Array [1] ".$array_tables[1]."<br>";
						//echo "Table Array [2] ".$array_tables[2]."<br>";
						//echo "There are ".$totalsize." rows in the watch list(s)<br>";
						//echo "We can expect to have to compare a total of ".$totalexpectedcycles." rows<br>";
						
		// STEP THREE: 
				//	With Step One and Step Two completed we have all trhe information we need to 
				//	start earching through the watch lists to see if there are any matchs to the 
				//	users CSV.
				
				//	Given that the user could have selected up to three different lists in three 
				//	seperate tables, we have no choice but to assume the user selected three and 
				//	loop through each of the lists as set in Step Two.
										
						for($k=0; $k<sizeof($array_tables); $k++) {									// The Big Picture Loop, Loops through 1 to 3 tables of data.
								$known_sids 		= 0;											// Depreciated, on path to deletion.
								$counter			= 0;											// Depreciated, on path to deletion.
								
				// Create the header row for this new Table	
								?>
			<tr>
				<td class="newTopicTitle">
					Match at least on
					</td>
				<td class="newTopicTitle">
					<?=$array_tables[$k];?>
					</td>
				<td class="newTopicTitle">
					SID
					</td>
				<td class="newTopicTitle">
					CLEARED
					</td>
				<td class="newTopicTitle">	
					LAST NAME
					</td>
				<td class="newTopicTitle">
					FIRST NAME
					</td>
				<td class="newTopicTitle">
					MIDDLE NAME
					</td>
				<td class="newTopicTitle">
					TYPE
					</td>
				<td class="newTopicTitle">
					DOB
					</td>
				<td class="newTopicTitle">
					POB
					</td>
				<td class="newTopicTitle">
					Citizanship
					</td>
				<td class="newTopicTitle">
					PASSPORT
					</td>
				<td class="newTopicTitle">
					MISC
					</td>
				</tr>
			<tr>
				<td colspan="13" class="newTopicBoxes">
					If the progress bar has completed to 100% and the window has disapeared, and no results are shown below. Then there are no matchs for your search.
					</td>
				</tr>
								<?
					
					// 	Inside of each table we need to compare the User's CSV to the People in the 
					//	given list. This is done via another for loop. For each member in the USV, Do.
								
								for($i=0; $i<sizeof($fcontents); $i++) {
										$line 				= trim($fcontents[$i],',');
										//$arr 				= explode(",",$line);
										
										//ini_set(magic_quotes_gpc, 0);
										
										$arr				= splitWithEscape($line);
										//echo "Tokens ".$tokens."";
										
										$tmp_last			= ($arr[0]);
										$tmp_first			= ($arr[1]);
										
										//$tmp_last 		= stripslashes($tmp_last); 
																				
										$link 				= mysql_connect("localhost", "webuser", "limitaces", "tsa_ruat");
		
										$loopcounter = 0;
										
											while (!$link) {
												echo "<p>Could not connect to the server </p>";
												echo mysql_error();
												echo "<br>LoopCounter is ".$loopcounter." <br>";
												echo "Attempting to reconnect<br>";
												
												$link = mysqli_connect("localhost", "webuser", "limitaces", "tsa_ruat");
												$loopcounter = $loopcounter+1;
												}

										
										//if ( get_magic_quotes_gpc() == 1 ):
										//	   stripslashes($tmp_last);
										//	   stripslashes($tmp_first);
										//		Endif;
										
										$tmp_last			= mysql_real_escape_string($tmp_last, $link);
										$tmp_first			= mysql_real_escape_string($tmp_first, $link);
										//$tmp_last 		= stripslashes($tmp_last); 
										
										//$tmp_last			= str_replace("'","",$tmp_last);
										//$tmp_first			= str_replace("'","",$tmp_first);
										
										//Echo "is magic quotes on? ".get_magic_quotes_gpc()."<br>";
										
										//$tmp_pob			= str_replace("\"","",$arr[5]);
										//$tmp_pob			= addslashes($tmp_pob);
										//$tmp_citizenship	= str_replace("\"","",$arr[6]);
										//$tmp_citizenship	= addslashes($tmp_citizenship);
										
										//echo "Tmp Last ".$tmp_last."<bR>";
										//echo "Tmp First ".$tmp_first."<bR>";
										//
										//echo "------------------------------------------------------------------<br>";
										//echo "New User Row in CSV, This is row [".$i."] of the CSV file <br>";
										//echo "RAW Data in this row is ".$line." <br>";
										//echo "Placing all elements of the row into an array <br>";
										//echo "Treating the 8th element of the array a little different <br>";
										//echo "The Array we will compare to the Watch list is ('".$arr[0]."','".$arr[1]."','".$arr[2]."','".$arr[3]."','".$arr[4]."','".$arr[5]."','".$arr[6]."','".$arr[7]."','".$tmp_misc."') <br>"; 
										
					//	Now we assemble the SQL Statement for this Search based on the information 
					//	in the users CSV file. Doing the SQL this way allows us to create a different 
					//	SQL statement for each row in the CSV. Important for fucntions latter in the program.										
										
									$sql = "SELECT * FROM ".$array_tables[$k]." WHERE ";
													
									// Alpha Test New SQL Statement				
													
									$doalphatest	= 1;	
									$dooldtest		= 1;
													
									if ($doalphatest == 1) {
											// Setup Alpha SQL Statement											
											// Psuedo Code											
											// is the frist name and last name like the first name and last name in the line?
											// a). ('FN' LIKE %x% AND 'LN' LIKE %y%) OR
											// What about the reverse?
											// b). ('FN' LIKE %y% AND 'LN' LIKE %x%) OR
											// is there more than one element in the first name?  if so combine them into one element
											// c). ('FN' LIKE %xx% AND 'LN' LIKE %y%) OR
											// is there more than one element in the last name?  if so combine them into one element
											// d). ('FN' LIKE %x% AND 'LN' LIKE %yy%) OR
										}
													
									if ($dooldtest == 1) {			
													
									// Get Requirements (1.B.1) & (1.B.2) & (1.B.3) & (1.B.4) & (1.B.5)
									
											// Do First and Last name Reversal
									
													$nsql = '(`ruat_tsa_last_name` 			LIKE CONVERT(_utf8 \'%'.$tmp_last.'%\' USING latin1) COLLATE latin1_swedish_ci ';
													//echo "<br> This line is ".$nsql."<br>";													
													$sql = $sql.$nsql;
													$nsql = ' AND `ruat_tsa_first_name` 	LIKE CONVERT(_utf8 \'%'.$tmp_first.'%\' USING latin1) COLLATE latin1_swedish_ci ) ';
													//echo "<br> This line is ".$nsql."<br>";	
													$sql = $sql.$nsql;
													
													
													$nsql = ' OR (`ruat_tsa_first_name` 	LIKE CONVERT(_utf8 \'%'.$tmp_last.'%\' USING latin1) COLLATE latin1_swedish_ci ';
													//echo "<br> This line is ".$nsql."<br>";	
													$sql = $sql.$nsql;
													$nsql = ' AND `ruat_tsa_last_name` 		LIKE CONVERT(_utf8 \'%'.$tmp_first.'%\' USING latin1) COLLATE latin1_swedish_ci ) ';
													//echo "<br> This line is ".$nsql."<br>";	
													$sql = $sql.$nsql;

											// Do First Name Concatenation
											
													$tmp_first_conc	= str_replace(" ", '',$tmp_first);
													
													$nsql = ' OR (`ruat_tsa_first_name` 	LIKE CONVERT(_utf8 \'%'.$tmp_first_conc.'%\' USING latin1) COLLATE latin1_swedish_ci ';
													//echo "<br> This line is ".$nsql."<br>";	
													$sql = $sql.$nsql;
													
													$nsql = ' AND `ruat_tsa_last_name` 		LIKE CONVERT(_utf8 \'%'.$tmp_last.'%\' USING latin1) COLLATE latin1_swedish_ci ) ';
													//echo "<br> This line is ".$nsql."<br>";	
													$sql = $sql.$nsql;
													
											// Do Last Name Concatenation
											
													$tmp_last_conc		= str_replace(" ", '',$tmp_last);
													
													$nsql = ' OR (`ruat_tsa_first_name` 	LIKE CONVERT(_utf8 \'%'.$tmp_first.'%\' USING latin1) COLLATE latin1_swedish_ci ';
													//echo "<br> This line is ".$nsql."<br>";	
													$sql = $sql.$nsql;
													
													$nsql = ' AND `ruat_tsa_last_name` 		LIKE CONVERT(_utf8 \'%'.$tmp_last_conc.'%\' USING latin1) COLLATE latin1_swedish_ci ) ';
													//echo "<br> This line is ".$nsql."<br>";	
													$sql = $sql.$nsql;
													
											// Title is irrelevent
											
											// Puncuation is ireelevent
									
									// Get Requirements (1.B.6)
											
											if ($arr[2]=="") {
													// Do nothing as there is no middle name to compare it to
												}
												else {	
													$combine_last 	= $arr[2]." ".$tmp_last;
													$combine_first 	= $tmp_first." ".$arr[2];	

													//echo "<br>Combines Last ".$combine_last."<br>";
													//echo "<br>Combines First ".$combine_first."<br>";

													
													$nsql = ' OR (`ruat_tsa_last_name` 	LIKE CONVERT(_utf8 \'%'.$combine_last.'%\' 	USING latin1) COLLATE latin1_swedish_ci ';
													//echo "<br> This line is ".$nsql."<br>";	
													$sql = $sql.$nsql;
													
													$nsql = ' AND `ruat_tsa_first_name` 	LIKE CONVERT(_utf8 \'%'.$tmp_first.'%\' USING latin1) COLLATE latin1_swedish_ci ) ';
													//echo "<br> This line is ".$nsql."<br>";	
													$sql = $sql.$nsql;
													
													$nsql = ' OR (`ruat_tsa_first_name` LIKE CONVERT(_utf8 \'%'.$combine_first.'%\' USING latin1) COLLATE latin1_swedish_ci ';
													//echo "<br> This line is ".$nsql."<br>";	
													$sql = $sql.$nsql;
													$nsql = ' AND `ruat_tsa_last_name` 	LIKE CONVERT(_utf8 \'%'.$tmp_last.'%\' 	USING latin1) COLLATE latin1_swedish_ci ) ';
													//echo "<br> This line is ".$nsql."<br>";	
													$sql = $sql.$nsql;	
													
													//$nsql = ' OR `ruat_tsa_middle_name` LIKE CONVERT(_utf8 \'%'.$arr[2].'%\' 		USING latin1) COLLATE latin1_swedish_ci ';
													//$sql = $sql.$nsql;
												}
										}	
													
													$sql = $sql." ORDER BY ruat_tsa_sid ";
													//echo "<br><br><font size='3'>SQL = ".$sql."</font><br><br>";
													$sql_encode = urlencode($sql);
													$user_s_who = $tmp_last.", ".$tmp_first." ".$arr[2];
													//echo "<br><br><font size='3'>SQL = ".$sql_encode."</font><br><br>";
													
													// Save This Search and Set email to yes
															$tmp_email 	= 1;
															$sql_date	= date('Y/m/d');
															$sql_name	= "Created on : ".$sql_date." ";
															
															$sql2 = "INSERT INTO tbl_users_searchs (user_s_name,user_s_who,user_s_table,user_s_sql,user_s_send_email, user_s_parent_id) VALUES ( '".$sql_name."','".$user_s_who."', '".$array_tables[$k]."', '".$sql_encode."', '".$tmp_email."', '".$_SESSION['user_id']."' )";
															//echo "SQL 2 ".$sql2." <br>";
															
															$mysqli = mysqli_connect("localhost", "webuser", "limitaces", "tsa_ruat");	
															$loopcounter3 = 0;
										
															while (!$mysqli) {
																echo "<p>Could not connect to the server </p>";
																echo mysql_error();
																echo "<br>LoopCounter is ".$loopcounter3." <br>";
																echo "Attempting to reconnect<br>";
																
																$mysqli = mysqli_connect("localhost", "webuser", "limitaces", "tsa_ruat");
																$loopcounter3 = $loopcounter2+1;
																}	
												
															if (mysqli_connect_errno()) {
																	// there was an error trying to connect to the mysql database
																	printf("connect failed: %s\n", mysqli_connect_error());
																	exit();
																}		
																else {
																	$objrs = mysqli_query($mysqli, $sql2) or die(mysqli_error($mysqli));
																	//printf("Last inserted record has id %d\n", LAST_INSERT_ID());
																	//echo mysql_insert_id($mysqli);
																	}
		
		//	STEP FOUR : With the previous steps completed we can dive into the actual job of searching 
		//	through the tables and seeing if there is a match to display.
		
					// 	The First part of the fourth step is to establish the connection with the 
					//	database.
					
										$objconn_support = mysqli_connect("localhost", "webuser", "limitaces", "tsa_ruat");
										
										$loopcounter2 = 0;
										
											while (!$objconn_support) {
												echo "<p>Could not connect to the server </p>";
												echo mysql_error();
												echo "<br>LoopCounter is ".$loopcounter." <br>";
												echo "Attempting to reconnect<br>";
												
												$objconn_support = mysqli_connect("localhost", "webuser", "limitaces", "tsa_ruat");
												$loopcounter2 = $loopcounter2+1;
												}										
										
										
										
										if (mysqli_connect_errno()) {
												printf("connect failed: %s\n", mysqli_connect_error());
												exit();
											}
											else {
												$objrs_support = mysqli_query($objconn_support, $sql);
												if ($objrs_support) {
														$number_of_rows = mysqli_num_rows($objrs_support);
														if ($number_of_rows == 0) {
																// Update the total number of queries the user has completed.
																updateuserqueries($_SESSION["user_id"]);
											
																// No Matchs have been found in this query, so increase the 
																//	totalrowsfound plus the number of rows plus the total number of rows in this search.											
																$totalcellsblanks = ( $totalcellsblanks + 1 );
											
																//$totalrowsprocessed = ($totalrowsprocessed + 1);
																//echo "There are no matches for ".$arr[0].", ".$arr[1]." <br><br><br>";
															}
															else {
																//echo "At least one match was found for ".$arr[0].", ".$arr[1]." <br><br><br>";
															}

					// 	Now Loop through each of the records in the database that were found as part
					//	of the search SQL. Determine if we already have seen them, and display accordingly.
					
														while ($newarray = mysqli_fetch_array($objrs_support, MYSQLI_ASSOC)) {
																// Update the total number of queries the user has completed.
																updateuserqueries($_SESSION["user_id"]);
																
																// Set a temporary variable that will be equal to the SID of the current found record
																$tmp_id = $newarray['ruat_tsa_sid'];
																
																// Looks like we are cycling through the found matches 
																// 	for the given search query. So add Plus 1 to the cells found
																$totalcellsfound = ($totalcellsfound + 1);
											
																// Check to see if our array of Found ID's already exisits	
																
																$debug = 1;
																if ($debug == 1 ) {
																		?>
																		<tr>
																			<td colspan="4" class="newTopicNames">
																				<font size="1"> Possible Match Found
																				</td>
																			<td class="newTopicNames">
																				<font size="1"><?=$arr[0];?>
																				</td>
																			<td class="newTopicNames">
																				<font size="1"><?=$arr[1];?>
																				</td>
																			<td class="newTopicNames">
																				<font size="1"><?=$arr[2];?>
																				</td>
																			<td class="newTopicNames">
																				<font size="1"><?=$arr[3];?>
																				</td>
																			<td class="newTopicNames">
																				<font size="1"><?=$arr[4];?>
																				</td>
																			<td class="newTopicNames">
																				<font size="1"><?=$arr[5];?>
																				</td>
																			<td class="newTopicNames">
																				<font size="1"><?=$arr[6];?>
																				</td>
																			<td class="newTopicNames">
																				<font size="1"><?=$arr[7];?>
																				</td>
																			<td class="newTopicNames">
																				<font size="1"><?=$arr[8];?>
																				</td>
																			</tr>
																			<?
																	}
																if ($idarray[$tmp_id] == '') {
																		//echo "Value does not exisit - Display Row";
																		$idarray[$tmp_id] = $tmp_id;
																		?>
			<tr>
				<td class="newTopicNames">
					WatchList Match Summary
					</td>	
				<td class="newTopicNames">
					<?=$array_tables[$k];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_sid'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_cleared'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_last_name'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_first_name'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_middle_name'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_type'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_DOB'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_last_POB'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_citizanship'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_passport'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_misc'];?>
					</td>
				</tr>												
																		<?
																	}
																	else {
																		//echo "This is a repeat row resulting from the SQL Statement
																	}

															}	// End of While Loop
													$counter = $counter + 1;
													//echo "Number of Rows Found Across all Cycles ".$totalcellsfound." <br>";
													//echo "Number of Empty Returns Across all Cycles ".$totalcellsblanks." <br>";
													//echo "There are ".$totalsize." rows in each cycle <br>";
													//echo "Increase tmp_a by the totalsize <br>";
													$tmp_a	= ( $tmp_a + $totalsize );
													//echo ">>> a > The Value is ".$tmp_a." <br>";
													//echo "The DIV layer is 300 pixels wide, what percent of the pixel is completed? <br>";
													$tmp_b	= ( (300 / $scobby) / $totalexpectedcycles );
													//echo ">>> b >The Vlaue is ".$tmp_b." <br>";
													//echo "Take the percentage times the amount of records completed, and round the result <br>";
													$tmp_c	= round(( $tmp_b * $tmp_a ),0);
													//echo ">>> c > The Value is ".$tmp_c." <br>";
													//echo "What percent of the progress bar is completed? <br>";
													$tmp_d	= ( round( ( $tmp_c / 300 ), 4 ) * 100 );
													//echo ">>> d > The value is ".$tmp_d." <br>";
													//echo "--------------------------------------------------------------------- <br>";										
													?>
																<script>														
																	document.getElementById("myDiv").style.display 		= "block";
																	document.getElementById("myDiv_bar").style.display 	= "block";
																	document.getElementById("myDiv_bar").style.width 	= "<?=$tmp_c;?>";
																	document.getElementById("percent").innerHTML 		= "<font color='#FFFFFF'><?=$tmp_d;?>%</font>";
																	//alert('You have unread messages \n Please check them by clicking the Notice Button');
																	</script>
													<?	
													}
												mysqli_free_result($objrs_support);
												mysqli_close($objconn_support);
											}
									$nsql = '';		
									//mysqli_free_result($objrs_support);											Was $link, still not functioning correctly
									//mysqli_close($objrs_support);													Was $link, still not functioning correctly
									}	// End of user's CSV For Loop Statement
							}	// End of Table Loop For Statement
					$time_a 	= timer();
					$tmp_time	= ($time_a - $time_b);
					//echo "This Query has taken ".$tmp_time." seconds to run ";
					?>
		<script>														
			document.getElementById("myDiv").style.display 		= "none";
			document.getElementById("myDiv_bar").style.display 	= "none";
			//alert('You have unread messages \n Please check them by clicking the Notice Button');
			</script>
				<?
	}
	else {
		// The form has not been submited yet
		?>
			<tr>
				<td>
					<?
					breadcrumbs('_addnewsearch.php', $pagename);
					$function = "Accessed Page: ".$pagename;
					put_systemactivity($_SESSION["user_id"],$function);
					?>
					</td>
				</tr>
			<tr>
				<td colspan="3" class="newTopicTitle">
					Add New Search Query
					</td>
				</tr>
			<tr>
				<td colspan="3" class="newTopicBoxes">
					<p>
						This page will allow you to create a new SD 1542-01-10E search of the TSA Watch Lists.
						</p>
					</td>
				</tr>
			<tr>
				<td colspan="3" class="newTopicBoxes">
					<table>
						<tr>						
							<form action="_managesearch.php" METHOD="POST" target="layouttableiframecontent" style="margin: 0px; margin-bottom:0px; margin-top:-1px;">
							<td class="newTopicBoxes">
								<input type="hidden" name="userid" 	value="<?=$_SESSION["user_id"];?>">
								<input type="hidden" name="displaylist" value="<?=$_POST['displaylist'];?>">
								<input type="hidden" name="displaypage" value="<?=$_POST['displaypage'];?>">
								<input type="submit" name="submit"	value="<<< Back" class="button">
								</td>
								</form>
							</tr>
						</table>
					</td>
				</tr>
			<tr>
				<td colspan="3" class="newTopicTitle">
					Add New SD 1542-01-10E Search
					</td>
				</tr>
			<tr>
				<td colspan="3" class="newTopicBoxes">
					<p>
						By clicking the 'Submit' button you will be generating an SD-01-10E watch list search. Your 
						search will be saved and an email will be emailed to you when a match is found in the 
						system. Be advised that this match will conform to the SD and will not match based on 
						anything other than the first name, last name, and middle name if provided.
						</p>
					</td>
				</tr>		
		<form action="_addnewsearch.php" Method="POST" style="margin: 0px; margin-bottom:0px; margin-top:-1px;">
			<input type="hidden" name="submitform" 	value="1">
			<input type="hidden" name="sd15420101e" value="YES">
			<input type="hidden" name="userid" 		value="<?=$_SESSION["user_id"];?>">				
			<tr>
				<td colspan="3" class="newTopicNames">
					<input type="submit" name="submit" value="submit" class="button">
					</td>					
				</tr>
			</form>
		<?
	}
	?>
			<tr>
				<td colspan="12" class="newTopicEnder">
					&nbsp;
					<?
					display_copywrite_footer();
					?>
					</td>
				</tr>	
			</table>
		</body>
	</html>
	<?
	}
	?>