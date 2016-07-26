define(["require","backbone"],function(require,Backbone){
    var pageType,
        listType,
        stations;
    var context =  {
        getPageConfig: function(pageType){

        },
        setUser:function(){

        },
        getListCols:function(type){
            return {
                station:[
                    { "data": "I",title:"总电流(A)",width:80 },
                    { "data": "U",title:"平均电压(V)",width:150 },
                    { "data": "T",title:"环境温度（℃）",width:100 },
                    { "data": "Humi",title:"环境湿度（%）",width:150 },
                    
                    { "data": "total",title:"组数",width:50  },
                    { "data": "batteryCount",title:"电池数",width:80  },
                    { "data": "BatteryHealth",title:"电池状态",width:70 },
                    { "data": "record_time",title:"时间",width:180 },
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
                    { "data": "TH",title:"环境温度上限（℃）",width:130 },
                    { "data": "TL",title:"环境温度下限（℃）",width:130 },
                    { "data": "HumiH",title:"环境湿度上限（%）",width:130 },
                    { "data": "HumiL",title:"环境湿度下限（%）",width:130 },
                    { "data": "ups_max_discharge",title:"最大放电电流（A）",width:70 },
                    { "data": "ups_max_charge",title:"最大充电电流（A）",width:70 },
                    /*{ "data": "B0_TH",title:"站环境温度超上限",width:130,render:function(data){return createCautionStatusHtml(data);} },
                     { "data": "B1_TL",title:"站环境温度超下限",width:130,render:function(data){return createCautionStatusHtml(data);} },
                     { "data": "B2_HumiH",title:"站环境湿度超上限",width:130,render:function(data){return createCautionStatusHtml(data);} },
                     { "data": "B3_HumiL",title:"站环境湿度超下限",width:130,render:function(data){return createCautionStatusHtml(data);} },
                     { "data": "B4_TtestFailed",title:"站环境温度测量失败",width:130,render:function(data){return createCautionStatusHtml(data);} },
                     { "data": "B5_noIsensor",title:"站未安装电流传感器",width:130,render:function(data){return createCautionStatusHtml(data);} },
                     { "data": "B6_LightSound_dev_error",title:"站声光报警设备的异常",width:150,render:function(data){return createCautionStatusHtml(data);} },
                     { "data": "B7_AirConditioner_HumiDev_Error",title:"站空调及加湿器控制异常",width:160,render:function(data){return createCautionStatusHtml(data);} },
                     { "data": "B8_NoConnection",title:"站无连接",width:100,render:function(data){return createCautionStatusHtml(data);} },
                     { "data": "B9_NoData_but_Connected",title:"站有链接无数据",width:130,render:function(data){return createCautionStatusHtml(data);} },
                     { "data": "BA_Data_Error",title:"站数据格式错误",width:130,render:function(data){return createCautionStatusHtml(data);} },
                     { "data": "BB_Offline_Often",title:"站掉线频繁",width:130,render:function(data){return createCautionStatusHtml(data);} },*/
                    //{ "data": "memo",title:"备注",width:330}
                ],
                group:[
                    
                    { "data": "GroupU",title:"电压（V）",width:100 },
                    { "data": "I",title:"电流（A）",width:100 },
                    { "data": "",title:"氢气浓度（%）",width:100 },
                    { "data": "",title:"氧气浓度（%）",width:100 },
                    { "data": "I",title:"电池电压均衡度",width:100 },
                    { "data": "I",title:"电池温度均衡度",width:100 },
                    { "data": "I",title:"电池内阻均衡度",width:100 },
                    { "data": "T",title:"组温度",width:100 },
                    { "data": "Humi",title:"组湿度",width:50 },
                    { "data": "batteryCount",title:"电池数",width:80  },
                    { "data": "record_time",title:"时间",width:150 },
                    { "data": "IoutMax",title:"最大放电电流（A）",width:150 },
                    { "data": "IinMax",title:"最大充电电流（A）",width:150 },
                    { "data": "I",title:"氢气浓度上限（%）",width:100 },
                    { "data": "I",title:"氧气上限",width:100 },
                    //{ "data": "BatteryHealth",title:"电池状态",width:100 },
                    { "data": "Tmax",title:"温度上限",width:100 },
                    { "data": "Tmin",title:"温度下限",width:100 },
                    { "data": "HumiHigh",title:"湿度上限",width:100 },
                    { "data": "HumiLow",title:"湿度下限",width:100 },
                    //{ "data": "Istatus",title:"电流状态",width:100 },
                    //{ "data": "Memo",title:"备注",width:300 }
                ],
                battery:[
                    // 充电态高压限（V）   ChargeStatus_U_upper
                    // 充电态低压限（V）   ChargeStatus_U_lower
                    // 浮充态高压限（V）   FloatingChargeStatus_U_upper
                    // 浮充态低压限（V）   FloatingChargeStatus_U_lower
                    // 放电态高压限（V）   DisChargeStatus_U_upper
                    // 放电态低压限（V）   DisChargeStatus_U_lower
                    { "data": "U",title:"电池电压（V）",width:150 },
                    { "data": "T",title:"电极温度（℃）",width:150 },
                    { "data": "R",title:"电池内阻（MΩ）",width:150 },
                    //{ "data": "AmpRange",title:"量程",width:80 },
                    { "data": "cau",title:"电压偏离（组均值）度%",width:150, render: function(data){return Math.abs(data*100).toFixed(2)} },
                    { "data": "cat",title:"温度偏离（组均值）度%",width:150, render: function(data){return Math.abs(data*100).toFixed(2)} },
                    { "data": "car",title:"内阻偏离（组均值）度%",width:150, render: function(data){return Math.abs(data*100).toFixed(2)} },
                    { "data": "",title:"预估容量（%）",width:150 },// 错误
                    { "data": "",title:"电池寿命（%）",width:150 },// 错误
                    { "data": "record_time",title:"时间",width:150 },
                    { "data": "FloatingbytegeStatus_U_upper",title:"浮充态电压上限",width:80 },
                    { "data": "bytegeStatus_U_upper",title:"充电状态电压上限",width:80 },// 错误
                    { "data": "DisbytegeStatus_U_upper",title:"放电态电压上限",width:80 },// 错误
                    { "data": "FloatingbytegeStatus_U_lower",title:"浮充态电压下限",width:80 },
                    { "data": "bytegeStatus_U_lower",title:"充电态电压下限",width:80 },// 错误
                    { "data": "DisbytegeStatus_U_lower",title:"放电态电压下限",width:80 },
                    { "data": "BatteryU_H",title:"温度上限",width:150 },
                    { "data": "BaterryU_L",title:"温度下限",width:150 },
                    { "data": "Rin_High_Limit",title:"内阻上限",width:150 },
                    /*
                    { "data": "N_R_measure_times",title:"内阻测量有效次数",width:150 },
                    { "data": "R_measure_error",title:"内阻测量状况",width:150 },
                    { "data": "Ie",title:"激励电流（A）",width:150 },
                    { "data": "NowStatus",title:"本次状态",width:100 },
                    { "data": "Memo",title:"前放量程",width:100 },
                    { "data": "B0bUH",title:"电压超上限",width:100,render:function(data){return createCautionStatusHtml(data);} },
                    { "data": "B1bUL",title:"电压超下限",width:100,render:function(data){return createCautionStatusHtml(data);} },
                    { "data": "B2bTH",title:"温度超上限",width:100,render:function(data){return createCautionStatusHtml(data);} },
                    { "data": "B3bTL",title:"温度超下限",width:100,render:function(data){return createCautionStatusHtml(data);} },
                    { "data": "B4bRH",title:"内阻超上限",width:100,render:function(data){return createCautionStatusHtml(data);} },
                    { "data": "B5bR_period_measure_errror",title:"电池周期性测内阻失败",width:200,render:function(data){return createCautionStatusHtml(data);} },
                    { "data": "B6bReserv",title:"备用6",width:100,render:function(data){return createCautionStatusHtml(data);} },
                    { "data": "B7bCommErr",title:"通讯异常",width:100,render:function(data){return createCautionStatusHtml(data);} },
                    { "data": "B8bUErr",title:"电压异常",width:100,render:function(data){return createCautionStatusHtml(data);} },
                    { "data": "B9bTErr",title:"温度异常",width:100,render:function(data){return createCautionStatusHtml(data);} },
                    { "data": "BAbRErr",title:"内阻异常",width:100,render:function(data){return createCautionStatusHtml(data);} },
                    { "data": "BBbCharge",title:"处于充电态",width:100,render:function(data){return createCautionStatusHtml(data);} },
                    { "data": "BCbDisCharge",title:"处于放电态",width:100,render:function(data){return createCautionStatusHtml(data);} },
                    { "data": "BDbNewR_flag",title:"新测的内阻",width:100,render:function(data){return createCautionStatusHtml(data);} },
                    { "data": "BEbR_measre_failed",title:"内阻测量失败",width:100,render:function(data){return createCautionStatusHtml(data);} },
                    { "data": "BFbReserve",title:"备用F",width:100},
                    { "data": "Memo",title:"备注",width:330}
                    */
                    /*{ "data": "AmpRange",title:"备注" },
                     { "data": "battery_state",title:"电压上限" },
                     { "data": "discharge_a_max",title:"电压下限（A）" },
                     { "data": "charge_a_max",title:"温度上限（A）" },
                     { "data": "group_hydrogen_max",title:"温度下限" },
                     { "data": "group_hydrogen_max",title:"内阻上限" }*/
                ]
            }[type];
        },
        getUser:function(){
            return this.get("user")
        },
        setPageType:function(type){
            pageType = type;
            Backbone.Events.trigger('pageType:change',type,window);
        },
        getListType:function(){
            return listType;
        },
        setCurStations:function(data){
            stations = data;
            Backbone.Events.trigger('curstation:change',data,window);
        },
        getCurStations:function(){
            return stations;
        }
    };


    function createCautionStatusHtml(code){
        return code == '1'?'<label style="color:#ff0000">是</label>':'<label style="color:#888">否</label>';
    }
    function createHasOrNotHtml(code){
        return code == '1'?'<label>是</label>':'<label>否</label>';
    }

    return context;
})