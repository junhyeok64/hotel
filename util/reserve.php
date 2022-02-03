<?php
class reserve {
	function room_check($room_cnt, $num, $sdate, $edate) {
		global $dbconn;

		$mode = "SUCC";
		$price = 0;
		$out = array();
		for($sdate; $sdate<$edate; $sdate = date("Y-m-d", strtotime($sdate." + 1 days"))) {
			$qry = "select num, price from reserve_check where room_type = '".$num."' and date = '".$sdate."' and cnt >= '".$room_cnt."'";
			$res = mysqli_query($dbconn, $qry);
			$row = @mysqli_fetch_array($res);
			if($row["num"] > 0) {
				$price += ($row["price"]*$room_cnt);
			} else {
				$mode = "FAIL";
			}
		}
		if($mode == "FAIL") {
			$out["mode"] = "FAIL";
			$out["msg"] = "선택한 일자중에 객실 마감된 일자가 포함 되어있습니다.";
			$out["price"] = 0;
		} else {
			$out["mode"] = "SUCC";
			$out["msg"] = "";
			$out["price"] = $price;
		}
		return $out;
	}
	function room_insert($data) {
		global $dbconn;
		$qry = "insert into reserve (room_type, room_cnt, reserve_name, phone, password, sdate, edate, reserve_time, userip, state, price) values";
		$qry .= "('".$data["room_type"]."','".$data["room_cnt"]."','".$data["reserve_name"]."','".$data["phone"]."','".$data["password"]."','".$data["sdate"]."','".$data["edate"]."',now(),'".$_SERVER["REMOTE_ADDR"]."','T', '".$data["price"]."')";
		$res = mysqli_query($dbconn, $qry);
		$data["num"] = mysqli_insert_id($dbconn);
		if($res) {
			$val = $this->room_min($data);
			//echo $val;
			if($val) {
				return true;
			}
		}
	}
	function room_min($data) {
		global $dbconn;
		foreach($data as $key=>$value) {
			$$key = $value;
		}
		$out = true;
		for($sdate; $sdate<$edate; $sdate = date("Y-m-d", strtotime($sdate." + 1 days"))) {
			$qry = "update reserve_check set cnt = cnt-".$room_cnt." where room_type = '".$room_type."' and date = '".$sdate."'";
			//echo $qry;
			$res = mysqli_query($dbconn, $qry);
			if(!$res) {
				$out = false;
				$e_qry = "insert into error_log (error_type, data, ip, data) values ('room_cnt_web', '".json_encode($data)."', '".$_SERVER["REMOTE_ADDR"]."', now())";
				$e_res = mysqli_query($dbconn, $e_qry);
			}
		}
		if($out == ture) {
			$u_qry = "update reserve set state = 'Y' where num = '".$num."'";
			$u_res = mysqli_query($dbconn, $u_qry);
		}
		return $out;
	}
}
?>