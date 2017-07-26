define(["require","backbone"],function(e,t){function o(e){return e=="1"?'<label style="color:#ff0000">是</label>':'<label style="color:#888">否</label>'}function u(e){return e=="1"?"<label>是</label>":"<label>否</label>"}var n,r,i,s={getPageConfig:function(e){},setUser:function(){},getListCols:function(e){return{station:[{data:"Current",title:"电流A",width:80},{data:"Voltage",title:"电压V",width:80},{data:"Temperature",title:"温度℃",width:80},{data:"Humidity",title:"湿度%",width:80},{data:"Lifetime",title:"寿命%",width:80},{data:"Capacity",title:"预估容量%",width:80},{data:"ChaState",title:"UPS状态",width:70,render:function(e){return e==1?"充电":e==2?"放电":"浮充"}},{data:"record_time",title:"时间",width:150},{data:"Groups",title:"组数",width:50},{data:"GroBats",title:"电池数",width:80},{data:"ups_power",title:"功率W/h",width:70},{data:"ups_maintain_date",title:"维护日期",width:100,render:function(e){var t=+(new Date(e)),n=+(new Date),r=(t-n)/1e3/60/60/24;return r<7&&r>0?'<div style="color:red">'+e+"</div>":e}},{data:"start_time",title:"放电开始",width:150,render:function(e){return e!=0?e:""}},{data:"end_time",title:"放电结束",width:150,render:function(e){return e!=0?e:""}},{data:"MaxTem_R",title:"温度上限℃",width:130},{data:"MinTem_R",title:"温度下限℃",width:130},{data:"MaxHum_R",title:"湿度上限%",width:130},{data:"MinHum_R",title:"湿度下限%",width:130},{data:"DisChaLim_R",title:"最大放电电流A",width:70},{data:"ChaLim_R",title:"最大充电电流A",width:70}],group:[{data:"Voltage",title:"电压V",width:100},{data:"Current",title:"电流A",width:100},{data:"Temperature",title:"平均温度℃",width:100},{data:"Humidity",title:"湿度%",width:50},{data:"Avg_U",title:"电压均值",width:100},{data:"Avg_T",title:"温度均值",width:100},{data:"Avg_R",title:"内阻均值",width:100},{data:"",title:"氢气浓度%",width:100},{data:"",title:"氧气浓度%",width:100},{data:"GroBats",title:"电池数",width:80},{data:"record_time",title:"时间",width:150},{data:"DisChaLim_R",title:"最大放电电流A",width:150},{data:"ChaLim_R",title:"最大充电电流A",width:150},{data:"",title:"氢气上限%",width:100},{data:"",title:"氧气上限%",width:100},{data:"MaxTem_R",title:"温度上限℃",width:100},{data:"MinTem_R",title:"温度下限℃",width:100},{data:"MaxHumi_R",title:"湿度上限%",width:100},{data:"MinHumi_R",title:"湿度下限%",width:100}],battery:[{data:"Voltage",title:"电压V",width:80},{data:"Temperature",title:"温度℃",width:80},{data:"Resistor",title:"内阻MΩ",width:100},{data:"Dev_U",title:"电压偏离",width:100,render:function(e){return Math.abs(e).toFixed(2)}},{data:"Dev_T",title:"温度偏离",width:100,render:function(e){return Math.abs(e).toFixed(2)}},{data:"Dev_R",title:"内阻偏离",width:100,render:function(e){return Math.abs(e).toFixed(2)}},{data:"Lifetime",title:"电池寿命%",width:100},{data:"Capacity",title:"容量%",width:100},{data:"record_time",title:"时间",width:150},{data:"MaxU_R",title:"浮充上限V",width:80},{data:"MaxDevU_R",title:"浮充偏差V",width:80},{data:"MinU_R",title:"放电下限V",width:80},{data:"MaxT_R",title:"温度上限℃",width:80},{data:"MinT_R",title:"温度下限℃",width:80},{data:"MaxR_R",title:"内阻上限MΩ",width:100}],qurey_station:[{data:"Current",title:"电流A",width:80},{data:"Voltage",title:"电压V",width:80},{data:"Temperature",title:"温度℃",width:80},{data:"Humidity",title:"湿度%",width:80},{data:"Lifetime",title:"寿命%",width:80},{data:"Capacity",title:"预估容量%",width:80},{data:"ChaState",title:"UPS状态",width:70,render:function(e){return e==1?"充电":e==2?"放电":"浮充"}},{data:"record_time",title:"时间",width:150},{data:"Groups",title:"组数",width:50},{data:"GroBats",title:"电池数",width:80},{data:"ups_power",title:"功率W/h",width:70},{data:"ups_maintain_date",title:"维护日期",width:100,render:function(e){var t=+(new Date(e)),n=+(new Date),r=(t-n)/1e3/60/60/24;return r<7&&r>0?'<div style="color:red">'+e+"</div>":e}},{data:"start_time",title:"放电开始",width:150,render:function(e){return e!=0?e:""}},{data:"end_time",title:"放电结束",width:150,render:function(e){return e!=0?e:""}},{data:"MaxTem_R",title:"温度上限℃",width:130},{data:"MinTem_R",title:"温度下限℃",width:130},{data:"MaxHum_R",title:"湿度上限%",width:130},{data:"MinHum_R",title:"湿度下限%",width:130},{data:"DisChaLim_R",title:"最大放电电流A",width:70},{data:"ChaLim_R",title:"最大充电电流A",width:70}],qurey_group:[{data:"Voltage",title:"电压V",width:100},{data:"Current",title:"电流A",width:100},{data:"Temperature",title:"平均温度℃",width:100},{data:"Humidity",title:"湿度%",width:50},{data:"Avg_U",title:"电压均值",width:100},{data:"Avg_T",title:"温度均值",width:100},{data:"Avg_R",title:"内阻均值",width:100},{data:"",title:"氢气浓度%",width:100},{data:"",title:"氧气浓度%",width:100},{data:"GroBats",title:"电池数",width:80},{data:"record_time",title:"时间",width:150},{data:"DisChaLim_R",title:"最大放电电流A",width:150},{data:"ChaLim_R",title:"最大充电电流A",width:150},{data:"",title:"氢气上限%",width:100},{data:"",title:"氧气上限%",width:100},{data:"MaxTem_R",title:"温度上限℃",width:100},{data:"MinTem_R",title:"温度下限℃",width:100},{data:"MaxHumi_R",title:"湿度上限%",width:100},{data:"MinHumi_R",title:"湿度下限%",width:100}],qurey_battery:[{data:"Voltage",title:"电压V",width:80},{data:"Temperature",title:"温度℃",width:80},{data:"Resistor",title:"内阻MΩ",width:100},{data:"Dev_U",title:"电压偏离",width:100,render:function(e){return Math.abs(e).toFixed(2)}},{data:"Dev_T",title:"温度偏离",width:100,render:function(e){return Math.abs(e).toFixed(2)}},{data:"Dev_R",title:"内阻偏离",width:100,render:function(e){return Math.abs(e).toFixed(2)}},{data:"Lifetime",title:"电池寿命%",width:100},{data:"Capacity",title:"容量%",width:100},{data:"record_time",title:"时间",width:150},{data:"MaxU_R",title:"浮充上限V",width:80},{data:"MaxDevU_R",title:"浮充偏差V",width:80},{data:"MinU_R",title:"放电下限V",width:80},{data:"MaxT_R",title:"温度上限℃",width:80},{data:"MinT_R",title:"温度下限℃",width:80},{data:"MaxR_R",title:"内阻上限MΩ",width:100}]}[e]},getUser:function(){return this.get("user")},setPageType:function(e){n=e,t.Events.trigger("pageType:change",e,window)},getListType:function(){return r},setCurStations:function(e){i=e,t.Events.trigger("curstation:change",e,window)},getCurStations:function(){return i}};return s});