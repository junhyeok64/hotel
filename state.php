<?php
	include "../config/hotel_config.php";
	foreach($_POST as $key => $value) {
		$$key = $value;
	}
	switch($mode) {
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
				echo "$('.current').eq(1).html('01');";
				echo "$('.current').eq(0).html('".$room_row["name"]."');";
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
		case "reserve_submit":	//예약완료
			$out = "";
			if($sdate && $edate && $room_cnt != 0 && $room_type && $reserve_name && $phone && $password) {
				//객실수량 다시한번 체크//reserve function 하나 파서 객실수량 체크 해야될듯 넘 자주함
				$room_chk = $reserve->room_check($room_cnt, $room_type, $sdate, $edate);
				if($room_chk["mode"] == "SUCC") {
					$_data = array("room_type", "room_cnt", "reserve_name", "phone", "password", "sdate", "edate", "price");
					$data = array();
					for($ac=0; $ac<count($_data); $ac++) {
						if($_data[$ac] == "price") {
							$data[$_data[$ac]] = $room_chk["price"];
						} else {
							$data[$_data[$ac]] = ${$_data[$ac]};
						}
					}
					$res = $reserve->room_insert($data);
					if($res) {
						$out = "alert('예약처리 되었습니다.');";
						$out .= "$(\"input[name='mode']\").val(\"price\");";
						$out .= "$(\"form[name='book_form']\").attr('action', '/hotel/mypage.php');";
						$out .= "$(\"form[name='book_form']\").attr('method', 'POST');";
						$out .= "$(\"form[name='book_form']\").submit();";
						$out .= "";
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
		case "reserve_check":	//mypage 예약확인
			$out = "";

			$qry = "select * from reserve where 1=1 ";
			$qry .= " and reserve_name = '".$reserve_name."'";
			$qry .= " and phone = '".$phone."'";
			$qry .= " and password = '".$password."'";
			$qry .= " order by num desc";
			$res = mysqli_query($dbconn, $qry);
			$cnt = @mysqli_num_rows($res);
			if($cnt > 0) {
				while($row = mysqli_fetch_array($res)) {
					$rqry = "select * from room where num = '".$row["room_type"]."'";
					$rres = mysqli_query($dbconn, $rqry);
					$rrow = mysqli_fetch_array($rres);
					$css = "";

					switch($row["state"]) {
						case "Y"://예약시도
							$state = "예약확인";
						break;
						case "P":
							$state = "결제완료";
						break;
						case "S":
							$state = "예약확정";
						break;
						case "C":
							$state = "예약최소";
							$css .= " sold_out";
						break;
						default:
						case "T":
							$state = "확인하기";
						break;
					}
					$price = ($row["price"] == "") ? 0 : @number_format($row["price"]);
					$sdate = new DateTime($row["sdate"]);
					$edate = new DateTime($row["edate"]);
					$night = date_diff($sdate, $edate);
					$night = $night->format("%d");
					$cnt = ($row["room_cnt"] > 1) ? " ( ".@number_format($row["room_cnt"])."개 )" : "";

					$out .= "
					<div class=\"col-lg-3 col-sm-6\">
                        <div class=\"accomodation_item text-center\">
                            <div class=\"hotel_img\">
                                <img src=\"".$rrow['img']."\" alt=\"\">
                                <a href=\"javascript:;\" class=\"btn theme_btn button_hover".$css."\">".$state."</a>
                            </div>
                            <a href=\"#\"><h4 class=\"sec_h4\">".$rrow["name"]."".$cnt."</h4></a>
                            <h5>".won." ".$price." <small>/ ".$night." night</small></h5>
                            <h6>".date("Y-m-d", strtotime($row["sdate"]))." ~ ".date("Y-m-d", strtotime($row["edate"]))."</h6>
                        </div>
                    </div>
					";
				}
			} else {
				$out = "
					<div class=\"col-lg-12\">
                        <div class=\"accomodation_item text-center\">
                            <h5>일치하는 예약건이 없습니다</h5>
                        </div>
                    </div>";
			}
			echo $out;
		break;
	}
?>