var stations = [
{ "data": "site_name", "title":"名称",width:150},
{ "data": "sid","title":"站号",width:50 },
{ "data": "record_time",title:"时间",width:180 },
{ "data": "I",title:"总电流(A)",width:80 },
{ "data": "U",title:"平均电压(V)",width:150 },
{ "data": "T",title:"环境温度（℃）",width:100 },
{ "data": "TH",title:"环境温度上限（℃）",width:130 },
{ "data": "TL",title:"环境温度下限（℃）",width:130 },
{ "data": "Humi",title:"环境湿度（%）",width:150 },
{ "data": "HumiH",title:"环境湿度上限（%）",width:130 },
{ "data": "HumiL",title:"环境湿度下限（%）",width:130 },
{ "data": "total",title:"组数",width:50  },
{ "data": "batteryCount",title:"电池数",width:80  },
{ "data": "BatteryHealth",title:"电池状态",width:70 },
{ "data": "charges",title:"UPS状态",width:70, render: function(data){
    if(data == 2){
        return "充电";
    }else if(data == 1){
        return "放电";
    }else{
        return "浮充";
    }
} },
{ "data": "BackupTime",title:"预估候备时间（H）",width:130 },
{ "data": "BackupW",title:"候备功率W/h",width:70, render: function(data){return ""} },
{ "data": "ups_maintain_date",title:"预约维护日期",width:70},
{ "data": "disChargeDate",title:"放电日期",width:70, render: function(data){return ""} },
{ "data": "disChargeLast",title:"放电时长",width:70, render: function(data){return ""} },
{ "data": "ups_max_discharge",title:"最大放电电流（A）",width:70 },
{ "data": "ups_max_charge",title:"最大充电电流（A）",width:70 }];

var alarm = [
{ "data": "alram_equipment",title:"站名" ,render:function(data,type,itemData){
    var color = ['red', 'green', '#f90']
    return '<span style="color:white;background-color:'+color[itemData.alarm_emergency_level -1]+'">'+itemData.alram_equipment+'</span>';
}},
{ "data": "alarm_para1_name",title:"站号" },
{ "data": "alarm_para2_name",title:"组号" },//组序列号
{ "data": "alarm_para3_name",title:"电池号" },
{ "data": "alarm_occur_time",title:"时间" },
{ "data": "alarm_content",title:"警情内容" },
{ "data": "alarm_para1_value",title:"数值" },
{ "data": "alarm_suggestion",title:"建议处理方式" }];

var batteryLife = [
{"data": "brand", title: "品牌", width: 100},
{"data": "battery_date", title: "生产日期", width: 100},
{"data": "battery_install_date", title: "电池安装日期", width: 100},
{"data": "U", title: "电池的电压", width: 100},
{"data": "battery_oum", title: "出厂标称内阻", width: 200},
{"data": "R", title: "电池的内阻", width: 100},
{"data": "battery_scrap_date", title: "强制报废日期"}];

function parse(datas){
	var EN = "ABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");
	datas.forEach(function(data,index){
		console.log(`->setCellValue('${EN[index]}1', '${data.title}')`);
	})
	console.log("*************************");
	datas.forEach(function(data,index){
		console.log(`->setCellValue('${EN[index]}'.$index, isset($v['${data.data}']) ? $v['${data.data}']:"")`);
	})
}

//parse(stations);
parse(batteryLife);