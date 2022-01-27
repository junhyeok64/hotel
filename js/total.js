var reserve = {
	booking : function(num, date, mode) {
		$("#reserve_detail").animate({
			opacity:1,
			height:"100%"
		});
		if($("#reserve_detail").hasClass("on") && mode != "select") {
			if($("input[name='sdate']").val() == "") {
				alert("투숙일을 확인해 주세요");
				$("input[name='sdate']").focus();
				return false;
			}
			if($("select[name='room_cnt']").val() == "" || $("select[name='room_cnt']").val() == 0) {
				alert("객실 수를 확인해 주세요");
				return false;
			}
			if($("select[name='room_cnt']").val() == "") {
				alert("객실 타입을 확인해 주세요");
				return false;
			}
			if($("input[name='reserve_name']").val() == "") {
				alert("예약자명을 입력해주세요");
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

		} else {
			$("#reserve_detail").addClass("on");
			reserve.booking_form("price", "book_form", "html");
		}
	},
	reserve_check : function(num, date) {
		$.ajax({
			type : "POST",
			data : {"mode":"reserve_check", "num":num, "date":date},
			url  : "state.php",
			success : function(e) {
				alert(e);
			}
		})
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
	booking_form : function(mode, form, type) {
		$.ajax({
			type : "POST",
			data : $("form[name='"+form+"']").serialize(),
			url  : "state.php",
			success : function(e) {
				switch(type) {
					case "html":
						$("#script").html(e);
					break;
				}
			}
		})
	},
	reserve_close: function() {
		$("#reserve_div").hide();
	}
}