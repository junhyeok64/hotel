var reserve = {
	booking : function(num, date) {

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
			data : {"mode":mode, $("form[name='"+form+"']").serialize()},
			url  : "state.php",
			success : function(e) {
				switch(type) {
					case "html":
						$("#script").html(e);
					break;
				}
			}
		})
	}
}