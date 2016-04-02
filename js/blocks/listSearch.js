define(['require','api','context'],function(require,API,context){
	return {
		init:function(pageType) {
			$( "#beginTime" ).datepicker({
				defaultDate: "+1w",
				changeMonth: true,
				onClose: function( selectedDate ) {
					$( "#endTime" ).datepicker( "option", "minDate", selectedDate );
				}
			});
			$( "#endTime" ).datepicker({
				defaultDate: "+1w",
				changeMonth: true,
				onClose: function( selectedDate ) {
					$( "#beginTime" ).datepicker( "option", "maxDate", selectedDate );
				}
			});

			$("#searchBtn").click(function(){

			})
		},
		getValue:function(){

		},
		isOver:function(){
			return true;
		}
	}
})