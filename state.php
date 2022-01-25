<?php
	include "../config/hotel_config.php";
	foreach($_POST as $key => $value) {
		$$key = $value;
	}
	switch($mode) {
		case "reserve_check":
			$cnt = ($cnt == "") ? 0 : $cnt;
			$qry = "select * from reserve_check where 1=1 ";
			$qry .= "and room_type = '".$num."' ";
			$qry .= "and date = '".$date."' ";
			$qry .= "and cnt >= '".$cnt."'";

			$res = mysqli_query($dbconn, $qry);
			$row = mysqli_num_rows($res);
			if($row>0) {
				$row = mysqli_fetch_array($res);
				echo "SUCC||".$row["cnt"];
			} else {
				echo "FAIL||";
			}
		break;
	}
?>