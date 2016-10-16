define(['require','api','context'],function(require,API,context){
	return {
		init:function(pageType) {
			$( "#beginTime" ).datetimepicker({
				//defaultDate: "+1w",
				changeMonth: true,
				showSecond:false,
				showMillisec:false,
				showMicrosec:false,
				maxDate:new Date(),
				onClose: function( selectedDate ) {
					$( "#endTime" ).datetimepicker( "option", "minDate", selectedDate );
				}
			});
			$( "#endTime" ).datetimepicker({
				//defaultDate: "+1w",
				changeMonth: true,
				showSecond:false,
				showMillisec:false,
				showMicrosec:false,
				maxDate:new Date(),
				onClose: function( selectedDate ) {
					$( "#beginTime" ).datetimepicker( "option", "maxDate", selectedDate );
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