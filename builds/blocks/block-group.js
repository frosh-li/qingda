define(["require","api","stationsinfoDialog","context","ui","table"],function(e,t,n,r,i){return{getExtObj:function(e){return{fetchData:function(e){t.getGroupsData(e)},render:function(){return this.listPlugin=[],this.listPlugin.push($("#lock table").DataTable($.extend(!0,{data:this.data,columnDefs:this.getColumnDefs(),columns:[{data:"name"},{data:"id"},{data:"group_id"},{data:"time"}]},e))),i.resizeAutoListWidth(),this.listPlugin.push($("#auto table").DataTable($.extend(!0,{data:this.data,scrollX:!0,columns:[{data:"a"},{data:"v"},{data:"t"},{data:"hydrogen"},{data:"battery_num"},{data:"battery_state"},{data:"discharge_a_max"},{data:"charge_a_max"},{data:"group_hydrogen_max"}]},e))),this}}}}});