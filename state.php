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
		case "booking_select":	//예약 일자 자동선택
			$qry = "select * from reserve_check where 1=1 ";
			$qry .= " and room_type = '".$num."'";
			$qry .= " and date = '".$date."'";
			//echo $qry;
			$res = mysqli_query($dbconn, $qry);
			$row = mysqli_fetch_array($res);

			$room_qry = "select * from room where num = '".$num."'";
			$room_res = mysqli_query($dbconn, $room_qry);
			$room_row = mysqli_fetch_array($room_res);
			echo "<script type=\"text/javascript\">";
			if($row["num"] > 0) {
				echo "$(\"input[name='sdate']\").val('".$date."').prop('selected', true);";
				echo "$(\"input[name='edate']\").val('".date("Y-m-d", strtotime($date." + 1 days"))."').prop('selected', true);";
				echo "$(\"select[name='room_type']\").val('".$num."').prop('selected', true);";
				echo "$(\"select[name='adult']\").val('1').prop('selected', true);";
				echo "$(\"select[name='child']\").val('0').prop('selected', true);";
				//echo "$(\"select[name='room_type']\").niceSelect();";
				echo "$('.current').eq(0).html('01');";
				echo "$('.current').eq(2).html('".$room_row["name"]."');";
			} else {
				echo "alert('해당 일자의 객실이 모두 마감되었습니다.');";
			}
			echo "</script>";
		break;
		case "booking":
			$out = "";
			$_sdate = $sdate;
			$_edate = $edate;
			$cnt = $adult+$child;
			$type = "SUCC";
			$out_sub = "";

			for($_sdate; $_sdate<$_edate; $_sdate = date("Y-m-d", strtotime(date("Y-m-d"), $_sdate." + 1 days"))) {
				$qry = "select * from reserve_check where room_type = '".$room_type."' and date = '".$_sdate."' and cnt >= '".$cnt."'";
				$res = mysqli_query($dbconn, $qry);
				$chk = @mysqli_num_rows($res);
				if($chk <= 0) {
					if($out_sub == "") {
						$out_sub = $_sdate."";
					} else {
						$out_sub = ",".$_sdate;
					}
					$type = "FAIL";
				}
			}
			if($type == "FAIL") {
				$out .= "alert('선택하신 일자중에 객실이 마감된 일자가 포함되어있습니다.\n".$out_sub."')";
			} else {
				$out .= "reserve_submit();";
			}
		break;
	}
?>