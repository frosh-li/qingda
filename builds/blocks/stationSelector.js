define(["require","api","backbone"],function(e,t,n){var r,i={source:function(e,r){t.getStationSlectorList({q:e.term}),n.Events.on("stationSelectorList:get",function(e){if(e&&e.list){var t=[];$.each(e.list,function(e,n){t.push({label:n,value:e})})}r(t)})},minLength:1},s;return{init:function(e){return e=e||{},s=$(e.ipt_id||"#stationSelector").autocomplete($.extend(i,e.extOption||{}))},destroy:function(){s.autocomplete("destroy")}}});