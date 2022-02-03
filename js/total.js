var reserve = {
	booking_index : function() {
		$("input[name='sdate']").focus();
		$("html body").animate({
			scrollTop : $("form[name='book_form']").offset().top
		},400)
	},
	booking : function(num, date, mode) {
		if($("#reserve_detail").hasClass("on") && mode != "select") {
			if($("input[name='sdate']").val() == "") {
				alert("투숙일을 확인해 주세요.");
				$("input[name='sdate']").focus();
				return false;
			}
			if($("select[name='room_cnt']").val() == "" || $("select[name='room_cnt']").val() == 0) {
				alert("객실 수를 확인해 주세요.");
				return false;
			}
			if($("select[name='room_type']").val() == "") {
				alert("객실 타입을 확인해 주세요.");
				return false;
			}
			if($("input[name='reserve_name']").val() == "") {
				alert("예약자명을 입력해주세요.");
				$("input[name='reserve_name']").focus();
				return false;
			}
			if($("input[name='phone']").val() == "") {
				alert("연락처를 확인 해 주세요\n추후 예약확인에 사용됩니다.");
				$("input[name='phone']").focus();
				return false;
			}
			if($("input[name='password']").val() == "") {
				alert("예약확인을 위한 비밀번호를 입력해주세요");
				$("input[name='password']").focus();
				return false;
			}
			$("input[name='mode']").val("reserve_submit");
			reserve.booking_form("book_form", "html");


		} else {
			if($("input[name='sdate']").val() == "") {
				alert("투숙일을 선택해주세요.");
				$("input[name='sdate']").focus();
				return;
			} else if($("select[name='room_type']").val() == "") {
				alert("객실 타입을 선택해주세요.");
				return;
			} else if($("select[name='room_cnt']").val() == "" || $("select[name='room_cnt']").val() == 0) {
				alert("객실 수를 확인해 주세요.");
				return;
			} else {
				$("#reserve_detail").animate({
					opacity:1,
					height:"100%"
				});
				$("#reserve_detail").addClass("on");
				reserve.booking_form("book_form", "html");
				$("html body").animate({
					scrollTop : $("form[name='book_form']").offset().top
				},400)
			}
		}
	},
	booking_state : function(num, date, mode, type) {
		$.ajax({
			type : "POST",
			data : {"mode":mode, "num":num, "date":date},
			url  : "state.php",
			success : function(e) {
				switch(type) {
					case "alert":
						alert(e);
					break;
					case "html":
						$("#script").html(e);
					break;
				}
			}
		})
	},
	booking_form : function(form, type) {
		$.ajax({
			type : "POST",
			data : $("form[name='"+form+"']").serialize(),
			url  : "state.php",
			success : function(e) {
				switch(type) {
					case "html":
						$("#script").html(e);
					break;
					case "reserve_check":
						$("#reserve_check").html(e);
					break;
				}
			}
		})
	},
	renewsletter : function() {
		if(confirm("메일 수신을 거부한 주소입니다. 다시 구독하겠습니까?")) {
			$("input[name='re']").val("Y");
			reserve.booking_form('newsletter', 'html');
		}
	},
	review_div : function(num) {
		if($("#review_pop").hasClass("on")) {
			$("#review_pop").hide();
      		$("#Mask").hide();
      		$("#review_pop").removeClass("on");
		} else {
			$("#review_pop").show();
      		$("#Mask").show();
      		$("#review_pop").addClass("on");
		}
		$("input[name='review_num']").val(num);
	}
}
$(document).ready(function(){
  $("input:text[numberOnly]").on("keyup", function() {
      $(this).val($(this).val().replace(/[^0-9]/g,""));
  });
});
$("input[name='password']").keyup(function(e){
    if(e.keyCode == 13) {
    	switch(page) {
    		case "index":
    		case "reserve":
    			reserve.booking();
    		break;
    		case "mypage":
    			reserve.booking_form('reserve', 'reserve_check');
    		break;
    	}
    }
});
$("#review_pop .close").click(function(){
	reserve.review_div('0');
});