<?
set_time_limit(0);

function timer()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function remove_unnecessary ($str, $char = "\n") {
    while (true) {
        if (substr ($str, -(strlen($char))) == $char) {
            $str = substr ($str, 0, -(strlen($char)));
        } else {
            break;
        }
    }
    
    return $str;
}

$t1 			= timer();
$tmpstarttime	= time();
		
$fcontents 		= file ('N912.csv'); 
$listing		= 'N912';

// Flush Table


$tmp_sizeoffile	= sizeof($fcontents);
echo "The file is ".$tmp_sizeoffile." rows. <br>";

for($i=0; $i<sizeof($fcontents); $i++) {
		$line 	= trim($fcontents[$i],',');
		$arr 	= explode(",",$line);

		$tmp_percentcompleted	= ($i / $tmp_sizeoffile);
		$tmp_percentcompleted	= (round($tmp_percentcompleted, 5) * 100);
		
		$objconn_support2 = mysqli_connect("localhost", "webuser", "limitaces", "tsa_ruat");
		$sql_support2 = "insert into tbl_nofly_list (ruat_tsa_sid,ruat_tsa_cleared,ruat_tsa_last_name,ruat_tsa_first_name,ruat_tsa_middle_name,ruat_tsa_type,ruat_tsa_DOB,ruat_tsa_last_POB,ruat_tsa_citizanship,ruat_tsa_passport,ruat_tsa_misc)
						VALUES 
						('".$arr[0]."','".$arr[1]."','".$arr[2]."','".$arr[3]."','".$arr[4]."','".$arr[5]."','".$arr[6]."','".$arr[7]."','".$arr[8]."','".$arr[9]."','".$arr[10]."')"; 	
		if (mysqli_connect_errno()) {
				// there was an error trying to connect to the mysql database
				printf("connect failed: %s\n", mysqli_connect_error());
				exit();
			}		
			else {
				//mysql_insert_id();
				$objrs_support2 	= mysqli_query($objconn_support2, $sql_support2) or die(mysqli_error($objconn_support2));
			}
	}

	$t8 = timer();
?>
Process takes <?=($t8 - $t1)/60;?> Minutes