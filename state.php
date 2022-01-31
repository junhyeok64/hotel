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
		case "booking_select":	//상품 클릭시 예약폼 자동완성
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
				echo "$(\"select[name='room_cnt']\").val('1').prop('selected', true);";
				//echo "$(\"select[name='child']\").val('0').prop('selected', true);";
				//echo "$(\"select[name='room_type']\").niceSelect();";
				echo "$('.current').eq(0).html('01');";
				echo "$('.current').eq(1).html('".$room_row["name"]."');";
				echo "$(\"input[name='reserve_name']\").focus();";
				echo "reserve.booking('','','select');";
			} else {
				echo "alert('해당 일자의 객실이 모두 마감되었습니다.');";
			}
			echo "</script>";
		break;
		case "price":
			//결제정보입력시 요금계산
			$out = "";
			$_sdate = $sdate;
			$_edate = $edate;
			$cnt = $room_cnt;
			$type = "SUCC";
			$out_sub = "";
			if($sdate > $edate) {
				echo "<script type=\"text/javascript\">";
				echo "$(\"input[name='edate']\").val('".date("Y-m-d", strtotime($sdate." + 1 days"))."');";
				echo "</script>";
				exit;
			}

			if($room_type != "" && $room_cnt != 0) {
				$room_chk = $reserve->room_check($room_cnt, $room_type, $sdate, $edate);
				$type = $room_chk["mode"];
				$out_sub = $room_chk["date"];
				if($type == "FAIL") {
					$out .= "alert('선택하신 일자중에 객실이 마감된 일자가 포함되어있습니다.(".$out_sub.")')";
				} else {
					$qry = "select sum(price) as price from reserve_check where room_type = '".$room_type."' and date >= '".$sdate."' and date < '".$edate."' and cnt >= '".$cnt."'";
					$res = mysqli_query($dbconn, $qry);
					$row = mysqli_fetch_array($res);
					$out .= "$('.reserve_pay').html(\"".won." <b>".@number_format(($row["price"])*$room_cnt)."</b>\");";
				}
			}
			echo "<script type=\"text/javascript\">";
			echo $out;
			echo "</script>";
		break;
		case "reserve_submit":
			$out = "";
			if($sdate && $edate && $room_cnt != 0 && $room_type && $reserve_name && $phone && $password) {
				//객실수량 다시한번 체크//reserve function 하나 파서 객실수량 체크 해야될듯 넘 자주함
				$room_chk = $reserve->room_check($room_cnt, $room_type, $sdate, $edate);
				if($room_chk["mode"] == "SUCC") {
					$qry = "insert into reserve (room_type, room_cnt, reserve_name, phone, password, sdate, edate, reserve_time, userip, state) values";
					$qry .= "('".$room_type."','".$room_cnt."','".$reserve_name."','".$phone."','".$password."','".$sdate."','".$edate."',now(),'".$_SERVER["REMOTE_ADDR"]."','Y')";
					$res = mysqli_query($dbconn, $qry);
					if($res) {
						$up_qry = "";

						$out = "alert('예약처리 되었습니다.');";
						$out .= "$(\"input[name='mode']\").val(\"price\");";
					} else {
						$out = "alert('잠시 후 다시 시도해주세요.');";
					}
				}
			} else {
				$out = "alert('예약자 정보를 확인해주세요.');";
			}
			echo "<script type=\"text/javascript\">";
			echo $out;
			echo "</script>";
		break;
	}
?>