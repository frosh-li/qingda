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
                    { "data": "I",title:"电流A",width:80 },
                    { "data": "U",title:"电压V",width:80 },
                    { "data": "T",title:"温度℃",width:80 },
                    { "data": "Humi",title:"湿度%",width:80 },
                    
                    
                    { "data": "Lifetime",title:"寿命%",width:80 },
                    { "data": "Capacity",title:"预估容量%",width:80 },
                    
                    { "data": "charge_state",title:"UPS状态",width:70, render: function(data){
                        if(data == 1){
                            return "充电";
                        }else if(data == 2){
                            return "放电";
                        }else{
                            return "浮充";
                        }
                    } },
                    { "data": "record_time",title:"时间",width:150 },
                    { "data": "total",title:"组数",width:50  },
                    { "data": "batteryCount",title:"电池数",width:80  },
                    { "data": "ups_power",title:"功率W/h",width:70 },
                    { "data": "ups_maintain_date",title:"维护日期",width:100},
                    { "data": "start_time",title:"放电开始",width:150, render: function(data){
                        return data != false ? data:""
                    } },
                    { "data": "end_time",title:"放电结束",width:150, render: function(data){return data != false ? data:""} },
                    { "data": "MaxTem_R",title:"温度上限℃",width:130 },
                    { "data": "MinTem_R",title:"温度下限℃",width:130 },
                    { "data": "MaxHum_R",title:"湿度上限%",width:130 },
                    { "data": "MinHum_R",title:"湿度下限%",width:130 },
                    { "data": "ups_max_discharge",title:"最大放电电流A",width:70 },
                    { "data": "ups_max_charge",title:"最大充电电流A",width:70 },
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
                    
                    { "data": "GroupU",title:"电压V",width:100 },
                    { "data": "I",title:"电流A",width:100 },
                    { "data": "T",title:"平均温度℃",width:100 },
                    { "data": "Humi",title:"湿度%",width:50 },
                    
                    { "data": "Avg_U",title:"电压均值",width:100 },
                    { "data": "Avg_T",title:"温度均值",width:100 },
                    { "data": "Avg_R",title:"内阻均值",width:100 },
                    { "data": "",title:"氢气浓度%",width:100 },
                    { "data": "",title:"氧气浓度%",width:100 },
                    { "data": "GroBatNum",title:"电池数",width:80  },
                    { "data": "record_time",title:"时间",width:150 },
                    { "data": "DisChaLim_R",title:"最大放电电流A",width:150 },
                    { "data": "ChaLim_R",title:"最大充电电流A",width:150 },
                    { "data": "I",title:"氢气上限%",width:100 },
                    { "data": "I",title:"氧气上限%",width:100 },
                    //{ "data": "BatteryHealth",title:"电池状态",width:100 },
                    { "data": "MaxTem_R",title:"温度上限℃",width:100 },
                    { "data": "MinTem_R",title:"温度下限℃",width:100 },
                    { "data": "MaxHumi_R",title:"湿度上限%",width:100 },
                    { "data": "MinHumi_R",title:"湿度下限%",width:100 },
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
                    { "data": "U",title:"电压V",width:80 },
                    { "data": "T",title:"温度℃",width:80 },
                    { "data": "R",title:"内阻MΩ",width:100 },
                    //{ "data": "AmpRange",title:"量程",width:80 },
                    { "data": "Dev_U",title:"电压偏离",width:100, render: function(data){return Math.abs(data*100).toFixed(2)} },
                    { "data": "Dev_T",title:"温度偏离",width:100, render: function(data){return Math.abs(data*100).toFixed(2)} },
                    { "data": "Dev_R",title:"内阻偏离",width:100, render: function(data){return Math.abs(data*100).toFixed(2)} },
                    { "data": "R",title:"电池寿命%",width:100, render:function(data, type,itemData){
                        var r0 = parseFloat(itemData.battery_oum);
                        var r = parseFloat(itemData.R);
                        var rho = parseFloat(itemData.MaxR_R);
                        var rhu = parseFloat(itemData.MaxR_Y);
                        var shu = 70;
                        var sho = 30;
                        var rj=30;
                        var ret = 100;

                        if(r <= r0){
                            ret = 100;
                        }else if(r < rhu){
                            console.log('yellow');
                            ret = 100 - (100 - shu) * (r-r0)/(rhu-r0)
                        }else if(r < rho){
                            console.log('red');
                            ret = shu - (shu-sho)*(r-rhu)/(rho-rhu)
                        }else if(r < rj){
                            ret = sho - sho * (r-rho)/(rj-rho)
                        }else{
                            ret = 0
                        }
                        return ret.toFixed(2)+"%";
                    } },// 错误
                    { "data": "R",title:"预估容量%",width:100, render:function(data, type,itemData){
                        var r0 = parseFloat(itemData.battery_oum);
                        var r = parseFloat(itemData.R);
                        var rho = parseFloat(itemData.MaxR_R);
                        var rhu = parseFloat(itemData.MaxR_Y);
                        var shu = 70;
                        var fz = 0;
                        var sho = 30;
                        var rj=30;
                        var ret = 100;
               
                        if(r <= r0){
                            fz = 1;
                            ret = 100;
                        }else if(r < rhu){

                            ret = 100 - (100 - shu) * (r-r0)/(rhu-r0)
                        }else if(r < rho){
   
                            ret = shu - (shu-sho)*(r-rhu)/(rho-rhu)
                        }else if(r < rj){
                            ret = sho - sho * (r-rho)/(rj-rho)
                        }else{
                            ret = 0
                        }
                        var i = 0.2;
                        var v0 = parseFloat(itemData.battery_voltage);//标准电压
                        var v = parseFloat(itemData.U);//当前电压
                        var sx = v0 + i;
                        var xx = v0 - i;
                        
                        var fl = 0,vhu,vho,hur,hor,vj;
                        if(v-sx > 0){
                            fl = 1;
                            vhu = parseFloat(itemData.MaxU_Y);
                            vho = parseFloat(itemData.MaxU_R);
                            hur = 95;
                            hor = 85;
                            vj = 15.8;

                        }else if(v-xx < 0){
                            fl = 2;
                            vhu = parseFloat(itemData.MinU_Y);
                            vho = parseFloat(itemData.MinU_R);
                            hur = 40;
                            hor = 20;
                            vj = 8;
                        }
                        var rl;
                        switch(fl){
                            case 1:
                            if(v < vhu){
                                rl = 100 - (100-hur) * (v-v0-i)/(vhu-v0-i)
                            }else if(v < vho){
                                rl = hur-(hur-hor)*(v-vhu)/(vho-vhu)
                            }else if(v < vj){
                                rl = hor - hor * (v - vho)/(vj-vho)
                            }else{
                                rl = 0
                            }
                            break;
                            case 2:
                            if(v-vhu > 0){
                                rl = 100 - (100 - hur)*(v - v0 + i) / (vhu - v0 +i)
                            }else if(v - vho > 0){
                                rl = hur - (hur - hor) * (v - vhu )/(vho - vhu)
                            }else if( v - vj > 0){
                                rl = hor - hor* (v-vho)/(vj - vho)
                            }else{
                                rl = 0
                            }
                            break;
                            case 0:
                            rl = 100
                            break;
                        }
                        if(fz == 0 ){
                            rl = rl * 0.5 * (1+ret / 100) * (ret/(ret+0.0001))
                        }
                        return rl.toFixed(2)+"%"
                    }},// 错误
                    { "data": "record_time",title:"时间",width:150 },
                    { "data": "battery_float_up",title:"浮充上限V",width:80 },
                    //{ "data": "bytegeStatus_U_upper",title:"充电状态电压上限",width:80 },// 错误
                    { "data": "battery_float_dow",title:"浮充下限V",width:80 },
                    { "data": "battery_discharge_down",title:"放电下限V",width:80 },
                    { "data": "MaxT_R",title:"温度上限℃",width:80 },
                    { "data": "MinT_R",title:"温度下限℃",width:80 },
                    { "data": "MaxR_R",title:"内阻上限MΩ",width:100 },
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