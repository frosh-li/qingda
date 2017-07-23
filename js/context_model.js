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
                    { "data": "Current",title:"电流A",width:80 },
                    { "data": "Voltage",title:"电压V",width:80 },
                    { "data": "Temperature",title:"温度℃",width:80},
                    { "data": "Humidity",title:"湿度%",width:80 },
                    { "data": "Lifetime",title:"寿命%",width:80 },
                    { "data": "Capacity",title:"预估容量%",width:80 },

                    { "data": "ChaState",title:"UPS状态",width:70, render: function(data){
                        if(data == 1){
                            return "充电";
                        }else if(data == 2){
                            return "放电";
                        }else{
                            return "浮充";
                        }
                    } },
                    { "data": "record_time",title:"时间",width:150
                    /*
                    , render:function(data){
                        var now = +new Date();
                        var originDate = +new Date(data);
                        if(now - originDate > 10*1000){
                            return '<div style="color:red">'+data+'</div>';
                        }else{
                            return '<div>'+data+'</div>'
                        }
                    }
                    */
                    },
                    { "data": "Groups",title:"组数",width:50  },
                    { "data": "GroBats",title:"电池数",width:80  },
                    { "data": "ups_power",title:"功率W/h",width:70 },
                    { "data": "ups_maintain_date",title:"维护日期",width:100, render:function(data){
                        var wh = +new Date(data);
                        var now = +new Date();
                        var dis = (wh - now)/1000/60/60/24;
                        if(dis < 7 && dis > 0){
                            return '<div style="color:red">'+data+'</div>'
                        }else{
                            return data;
                        }
                    }},
                    { "data": "start_time",title:"放电开始",width:150, render: function(data){
                        return data != false ? data:""
                    } },
                    { "data": "end_time",title:"放电结束",width:150, render: function(data){return data != false ? data:""} },
                    { "data": "MaxTem_R",title:"温度上限℃",width:130 },
                    { "data": "MinTem_R",title:"温度下限℃",width:130 },
                    { "data": "MaxHum_R",title:"湿度上限%",width:130 },
                    { "data": "MinHum_R",title:"湿度下限%",width:130 },
                    { "data": "DisChaLim_R",title:"最大放电电流A",width:70 },
                    { "data": "ChaLim_R",title:"最大充电电流A",width:70 },
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

                    { "data": "Voltage",title:"电压V",width:100 },
                    { "data": "Current",title:"电流A",width:100 },
                    { "data": "Temperature",title:"平均温度℃",width:100 },
                    { "data": "Humidity",title:"湿度%",width:50 },

                    { "data": "Avg_U",title:"电压均值",width:100 },
                    { "data": "Avg_T",title:"温度均值",width:100 },
                    { "data": "Avg_R",title:"内阻均值",width:100 },
                    { "data": "",title:"氢气浓度%",width:100 },
                    { "data": "",title:"氧气浓度%",width:100 },
                    { "data": "GroBats",title:"电池数",width:80  },
                    { "data": "record_time",title:"时间",width:150 },
                    { "data": "DisChaLim_R",title:"最大放电电流A",width:150 },
                    { "data": "ChaLim_R",title:"最大充电电流A",width:150 },
                    { "data": "",title:"氢气上限%",width:100 },
                    { "data": "",title:"氧气上限%",width:100 },
                    //{ "data": "BatteryHealth",title:"电池状态",width:100 },
                    { "data": "MaxTem_R",title:"温度上限℃",width:100 },
                    { "data": "MinTem_R",title:"温度下限℃",width:100 },
                    { "data": "MaxHumi_R",title:"湿度上限%",width:100 },
                    { "data": "MinHumi_R",title:"湿度下限%",width:100 },
                    //{ "data": "Istatus",title:"电流状态",width:100 },
                    //{ "data": "Memo",title:"备注",width:300 }
                ],
                battery:[
                    { "data": "Voltage",title:"电压V",width:80 },
                    { "data": "Temperature",title:"温度℃",width:80 },
                    { "data": "Resistor",title:"内阻MΩ",width:100 },
                    //{ "data": "AmpRange",title:"量程",width:80 },
                    { "data": "Dev_U",title:"电压偏离",width:100, render: function(data){return Math.abs(data).toFixed(2)} },
                    { "data": "Dev_T",title:"温度偏离",width:100, render: function(data){return Math.abs(data).toFixed(2)} },
                    { "data": "Dev_R",title:"内阻偏离",width:100, render: function(data){return Math.abs(data).toFixed(2)} },
                    { "data": "Lifetime",title:"电池寿命%",width:100 },
                    { "data": "Capacity",title:"容量%",width:100 },
                    { "data": "record_time",title:"时间",width:150 },
                    { "data": "battery_float_up",title:"浮充上限V",width:80 },
                    { "data": "battery_float_dow",title:"浮充下限V",width:80 },
                    { "data": "battery_discharge_down",title:"放电下限V",width:80 },
                    { "data": "MaxT_R",title:"温度上限℃",width:80 },
                    { "data": "MinT_R",title:"温度下限℃",width:80 },
                    { "data": "MaxR_R",title:"内阻上限MΩ",width:100 }
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