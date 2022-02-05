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
					$css = $link = "";
					$link = "javascript:;";

					switch($row["state"]) {//'Y','S','C','T'
						case "Y"://예약시도
							$state = "예약확인";
						break;
						case "P":
							$state = "결제완료";
						break;
						case "S":
							$state = "예약확정";
							if(date("Y-m-d", strtotime($row["edate"])) < date("Y-m-d")) {
								$review_qry = "select * from review where reserve_num = '".$row["num"]."'";
								$review_res = mysqli_query($dbconn, $review_qry);
								$review_cnt = @mysqli_num_rows($review_res);
								if($review_cnt > 0) {
									$state = "리뷰완료";
									$css = " sold_out";
									$link = "javascript:;";
								} else {
									$state = "리뷰작성";
									$css = " success";
									$link = "javascript:reserve.review_div('".$row["num"]."');";
								}
							}
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
                                <a href=\"".$link."\" class=\"btn theme_btn button_hover".$css."\">".$state."</a>
                            </div>
                            <a href=\"".$link."\"><h4 class=\"sec_h4\">".$rrow["name"]."".$cnt."</h4></a>
                            <h5>".won." ".$price." <small>/ ".$night." night</small></h5>
                            <h6>".date("Y-m-d", strtotime($row["sdate"]))." ~ ".date("Y-m-d", strtotime($row["edate"]))."</h6>
                        </div>
                    </div>
					";
				}
			} else {
				$out = "
					<div class=\"col-lg-12\">
                        <div class=\"accomodation_item text-center\" style=\"margin-bottom:10rem;\">
                            <h5>일치하는 예약건이 없습니다</h5>
                            <h5 style='padding-top:5px;'><a href=\"javascript:location.href='".base_url."/reserve.php';\">다른객실 살펴보기</a></h5>
                        </div>
                    </div>";
			}
			$out .= "<script type=\"text/javascript\">";
			$out .= "
			$(document).ready(function(){
				$('html, body').animate({scrollTop: $(\"#reserve_check\").offset().top-300}, 400);
			})
			";
			$out .= "</script>";
			echo $out;
		break;
		case "newsletter"://푸터 뉴스레터받기
			$out = "<script type=\"text/javascript\">";
			if($email != "") {
				$sel_qry = " select * from news where 1=1 ";
				$sel_qry .= " and email = '".$email."'";
				$sel_res = mysqli_query($dbconn, $sel_qry);
				$sel_row = @mysqli_fetch_array($sel_res);
				if($sel_row["email"] != "") {
					if($sel_row["state"] == "Y") {
						$out .= "alert('이미 등록된 메일입니다.');";
					} else {
						if($re == "Y") {
							$up_qry = "update news set state = 'Y', udate = now() where email = '".$email."'";
							$up_res = mysqli_query($dbconn, $up_qry);
							if($up_res) {
								$out .= "alert('재구독완료');";
								$out .= "$(\"input[name='re']\").val('');";
							} else {
								$out .= "alert('잠시 후 다시 시도해주세요');";
							}

						} else {
							$out .= "reserve.renewsletter();";
						}
					}
				} else {
					$in_qry = "insert into news (email, wdate, state) values ";
					$in_qry .= "('".$email."', now(), 'Y')";
					$in_res = mysqli_query($dbconn, $in_qry);
					if($in_res) {
						$out .= "alert('구독완료');";
					}
				}
			}
			$out .= "</script>";
			echo $out;
		break;
		case "review_write":
			$message = addslashes($message);
			$in_qry = "insert into review (reserve_num, contents, wdate, userip) values ";
			$in_qry .= "('".$review_num."', '".$message."', now(), '".$_SERVER["REMOTE_ADDR"]."')";
			$in_res = mysqli_query($dbconn, $in_qry);
			//"test"
			$out = "";
			$out .= "<script type=\"text/javascript\">";
			if($in_res) {
				$out .= "alert('작성완료');";
				$out .= "reserve.booking_form('reserve', 'reserve_check');";
				$out .= "$(\"textarea[name='message']\").val('');";
				$out .= "reserve.review_div('0');";
			} else {
				$out .= "alert('잠시 후 다시 시도해주세요.')";
			}
			$out .= "</script>";
			echo $out;
		break;
	}
?>