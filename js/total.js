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
	}
}