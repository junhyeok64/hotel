<?php
	include "../../config/hotel_config.php";

	$sdate = date("Y-m-d");
	$edate = date("Y-m-d", strtotime($sdate."+ 89 days"));

	echo $sdate."||".$edate;

	$qry = "insert into reserve_check (room_type, date, cnt, price) values ";
	for($sdate; $sdate<=$edate; $sdate = date("Y-m-d", strtotime($sdate." + 1 days"))) {
		if(in_array(date("w", $sdate), array(0,6))) {
			$qry .= "('2', '".$sdate."', '10', '65000'),";
		} else {
			$qry .= "('2', '".$sdate."', '10', '45000'),";
		}
	}
	echo $qry;
?>