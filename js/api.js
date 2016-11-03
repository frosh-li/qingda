define(function(require){
    var $ = require('jquery'),
        API = {
            isContained(a, b){
                if(!(a instanceof Array) || !(b instanceof Array)) return false;
                if(a.length < b.length) return false;
                var aStr = a.toString();
            console.info(aStr);
                for(var i = 0, len = b.length; i < len; i++){
            console.info(aStr.indexOf(b[i]));
                   if(aStr.indexOf(b[i]) == -1) return false;
                }
                return true;
            },
            fetch: function (url, event, data, type, context,unalert, cb) {
                var _this = this;
                $.ajax({
                    type: type || 'GET',
                    data: $.extend(true,{},data),
                    //jsonp: 'callback',
                    url: url,
                    success: function (res) {
                        typeof res == 'string' && (res = $.parseJSON(res));
                        if (!!res && typeof res === 'object') {
                            if(res.response.code == 0){
                                if(cb){
                                    res.data.callback = cb;
                                }
                                if(url == "/api/index.php/stationperson/index"){
                                    var filterdata = [];
                                    var userid = JSON.parse(localStorage.getItem("userinfo")).uid;
                                    var currentUser = null;
                                    
                                    $.each(res.data.list,function(i,item){
                                        console.log(i,item);
                                        if(item.id == userid){
                                            currentUser = item;
                                            return;
                                        }
                                    });
                                    console.log('currentUser', currentUser);
                                    if(currentUser.area== "*"){
                                        filterdata = res.data.list;
                                    }else{
                                        $.each(res.data.list,function(i,item){
                                            if(item.area != "*"){
                                                console.log(currentUser.area, item.area)
                                                if(_this.isContained(currentUser.area.split(","), item.area.split(","))){
                                                    filterdata.push(item);
                                                }
                                            }
                                        })
                                    }
                                    res.data = {
                                        list: filterdata
                                    }
                                }
                                Backbone.Events.trigger(event, res.data, context);
                                if(cb){
                                    cb();
                                }
                            }else if ( res.response.code == "-100") {
                                route.navigate('login',{trigger:true});
                            }else{
                                Backbone.Events.trigger(event+":fail", res, context);
                                if(unalert){
                                    return;
                                }
                                // alert(res.response.msg);
                            }
                        } else {
                            Backbone.Events.trigger(event,"error", res.response.message || res.response.msg);
                        }
                    },
                    error: function (res) {
                        //Backbone.Events.trigger(event, $.evalJSON(res.responseText), context);
                        //TODO:正式联调时替换为下列
                        console.log(res);
                        Backbone.Events.trigger("messager", {ret: 1, massage: url, data: []}, context || window);
                    }
                });
            },
            /***************************************获取底部状态栏***************************************/
            stat: function () {
                var url = '/api/index.php/stat/info';
                this.fetch(url, "stat", "get");
                return this;
            },
            /***************************************登陆***************************************/
            login: function (args) {
                var url = '/api/index.php/login';
                if(args.refresh){
                    this.fetch(url, "login:box", args, "post");
                }else{
                    this.fetch(url, "login", args, "post");
                }

                return this;
            },
            /***************************************地图***************************************/
            getMapData: function (args,event) {
                var url = '/api/index.php/map/sites';
                this.fetch(url, event||"mapdata:update", args, "get");
                return this;
            },
            /***************************************左侧导航***************************************/
            getNavData: function (cb,args) {
                var url = '/api/index.php/trees/getnav';
                this.fetch(url, "nav:update", args, "get",this,true, cb);
                return this;
            },
            /***************************************人员角色***************************************/
            getRolesData:function (args) {
                var url = '/api/index.php/role/index';
                this.fetch(url, "listdata:update", args, "get");
                return this;
            },
            getPersonalsData:function (args) {
                var url = '/api/index.php/stationperson/index';
                this.fetch(url, "listdata:update", args, "get");
                return this;
            },
            getPersonalInfo:function(args) {
                var url = '/api/index.php/stationperson/view';
                this.fetch(url, "personalInfo:get", args, "get");
                return this;
            },
            updatePersonalInfo:function(args) {
                var url = '/api/index.php/stationperson/update';
                this.fetch(url, "personal:update", args, "post");
                return this;
            },
            createPersonal:function(args) {
                var url = '/api/index.php/stationperson/create';
                this.fetch(url, "personal:create", args, "post");
                return this;
            },
            deletePersonal:function(args) {
                var url = '/api/index.php/stationperson/delete';
                this.fetch(url, "listitem:delete", args, "get");
                return this;
            },
            /***************************************树形图***************************************/
            getTreeInfo: function (args) {
                var url = '/api/index.php/trees';
                this.fetch(url, "tree:get", args, "get");
                return this;
            },
            updateTree: function (args) {
                var url = '/api/index.php/trees/update';
                this.fetch(url, "tree:update", args, "get");
                return this;
            },
            createTree: function (args) {
                var url = '/api/index.php/trees/create';
                this.fetch(url, "tree:create", args, "get");
                return this;
            },
            deleteTree: function (args) {
                var url = '/api/index.php/trees/delete';
                this.fetch(url, "tree:delete", args, "get");
                return this;
            },
            /***************************************站***************************************/
            getStationRealTimeData:function(args){
                var url = '/api/index.php/realtime';
                this.fetch(url, "stationdata:get", args, "get");
                return this;
            },
            getStationHistoryData:function(args){
                var url = '/api/index.php/query';
                this.fetch(url, "stationdata:get", args, "get");
                return this;
            },
            getGroupHistoryData:function(args){
                var url = '/api/index.php/query/groupmodule';
                this.fetch(url, "listdata:update", args, "get");
                return this;
            },
            getBatteryHistoryData:function(args){
                var url = '/api/index.php/query/batterymodule';
                this.fetch(url, "listdata:update", args, "get");
                return this;
            },
            getLinkingStationNum:function(args,event){
                var url = '/api/index.php/stat';
                this.fetch(url, event||"linknum:get", args, "get");
                return this;
            },
            createStation:function(args,events){
                var url = '/api/index.php/sites/create';
                this.fetch(url, events||"stationdata:create", args, "POST");
                return this;
            },
            updateStation:function(args){
                var url = '/api/index.php/sites/update';
                this.fetch(url, "stationdata:update", args, "POST");
                return this;
            },
            deleteStation:function(args){
                var url = '/api/index.php/sites/delete';
                this.fetch(url, "stationdata:delete", args, "POST");
                return this;
            },
            getNewStationData: function(args){
                var url = '/api/index.php/sites/newstations';
                this.fetch(url, "listdata:update", args, "get");
                return this;
            },
            checkStation:function(args){
                var url = '/api/index.php/sites/check';
                this.fetch(url, "station:check", args, "get");
                return this;
            },
            getStationSlectorList: function (args) {
                var url = '/api/index.php/sites/suggest';
                this.fetch(url, "stationSelectorList:get", args, "get");
                return this;
            },
            getStationInfo: function (args) {
                var url = '/api/index.php/map/sitesinfo';
                this.fetch(url, "stationinfo:update", args, "get");
                return this;
            },
            getStationsInfo: function (args) {
                var url = '/api/index.php/sites';
                this.fetch(url, "listdata:update", args, "get");
                return this;
            },
            getStationEditInfo: function (args) {
                var url = '/api/index.php/sites/view';
                this.fetch(url, "stationinfo:foredit:update", args, "get");
                return this;
            },
            getStationOptionEditInfo:  function (args) {
                var url = '/api/index.php/stationpara/view';
                this.fetch(url, "stationoption:get", args, "get");
                return this;
            },
            getSationOptionsData: function (args) {
                var url = '/api/index.php/stationpara/index';
                this.fetch(url, "listdata:update", args, "get");
                return this;
            },
            updateStationOption:function(args){
                var url = '/api/index.php/stationpara/update';
                this.fetch(url, "stationoption:update", args, "POST");
                return this;
            },
            /***************************************组***************************************/
            getGroupsData: function (args) {
                var url = '/api/index.php/realtime/groupmodule';
                this.fetch(url, "listdata:update", args, "get");
                return this;
            },
            getGroupOptionData:function (args) {
                var url = '/api/index.php/grouppara/index';
                this.fetch(url, "listdata:update", args, "get");
                return this;
            },
            getGroupOption:function (args) {
                var url = '/api/index.php/grouppara/view';
                this.fetch(url, "groupoption:get", args, "get");
                return this;
            },
            updateGroupOption:function (args) {
                var url = '/api/index.php/grouppara/update';
                this.fetch(url, "groupoption:update", args, "post");
                return this;
            },
            /***************************************短信邮箱***************************************/
            getMessagesData:function (args) {
                var url = '/api/index.php/alarmset/config';
                this.fetch(url, "listdata:update", args, "get");
                return this;
            },
            getMessageInfo:function(args) {
                var url = '/api/index.php/alarmset/view';
                this.fetch(url, "messageInfo:get", args, "get");
                return this;
            },
            updateMessage:function(args) {
                var url = '/api/index.php/alarmset/updateMsg';
                this.fetch(url, "message:update", args, "post");
                return this;
            },
            createMessage:function(args) {
                var url = '/api/index.php/alarmset/create';
                this.fetch(url, "message:create", args, "post");
                return this;
            },
            deleteMessage:function(args) {
                var url = '/api/index.php/alarmset/delete';
                this.fetch(url, "listitem:delete", args, "get");
                return this;
            },
            /***************************************电池***************************************/
            getBatterysRealTimeData: function (args) {
                var url = '/api/index.php/realtime/batterymodule';
                this.fetch(url, "listdata:update", args, "get");
                return this;
            },
            getBatteryInfosData:function(args) {
            	var url = '/api/index.php/batteryinfo/index';
                this.fetch(url, "listdata:update", args, "get");
                return this;
            },
            getBatteryInfo:function(args) {
            	var url = '/api/index.php/batteryinfo/view';
                this.fetch(url, "batteryInfo:get", args, "get");
                return this;
            },
            getBatteryOptionsData:function(args) {
            	var url = '/api/index.php/batterypara';
                this.fetch(url, "listdata:update", args, "get");
                return this;
            },
            getBatteryOption:function(args) {
            	var url = '/api/index.php/batterypara/view';
                this.fetch(url, "batteryoption:get", args, "get");
                return this;
            },
            updateBatteryOption:function(args){
                var url = '/api/index.php/batterypara/update';
                this.fetch(url, "batteryoption:update", args, "POST");
                return this;
            },
            createBattery:function(args,event){
                var url = '/api/index.php/batteryinfo/create';
                this.fetch(url, event||"battery:create", args, "POST");
                return this;
            },
            updateBatteryInfo:function(args){
                var url = '/api/index.php/batteryinfo/update';
                this.fetch(url, "batteryInfo:update", args, "POST");
                return this;
            },
            deleteBattery:function(args){
                var url = '/api/index.php/batteryinfo/delete';
                this.fetch(url, "listitem:delete", args, "POST");
                return this;
            },
            /***************************************BMS***************************************/
            getBMSInfosData:function(args){
                var url = '/api/index.php/bmsinfo';
                this.fetch(url, "listdata:update", args, "get");
                return this;
            },
            getBMSInfo:function(args) {
            	var url = '/api/index.php/bmsinfo/view';
                this.fetch(url, "bmsInfo:get", args, "get");
                return this;
            },
            updateBMSInfo:function(args) {
            	var url = '/api/index.php/bmsinfo/update';
                this.fetch(url, "bms:update", args, "post");
                return this;
            },
            createBMS:function(args) {
            	var url = '/api/index.php/bmsinfo/create';
                this.fetch(url, "bms:create", args, "post");
                return this;
            },
            deleteBMS:function(args) {
            	var url = '/api/index.php/bmsinfo/delete';
                this.fetch(url, "listitem:delete", args, "get");
                return this;
            },
            /***************************************用户单位信息***************************************/
            getCompanyInfosData:function(args){
                var url = '/api/index.php/companyinfo';
                this.fetch(url, "listdata:update", args, "get");
                return this;
            },
            getCompanyInfo:function(args) {
            	var url = '/api/index.php/companyinfo/view';
                this.fetch(url, "companyInfo:get", args, "get");
                return this;
            },
            updateCompanyInfo:function(args) {
            	var url = '/api/index.php/companyinfo/update';
                this.fetch(url, "company:update", args, "post");
                return this;
            },
            createCompany:function(args) {
            	var url = '/api/index.php/companyinfo/create';
                this.fetch(url, "company:create", args, "post");
                return this;
            },
            deleteCompany:function(args) {
            	var url = '/api/index.php/companyinfo/delete';
                this.fetch(url, "listitem:delete", args, "get");
                return this;
            },
            /***************************************UPS***************************************/
            getUpsInfosData:function(args) {
            	var url = '/api/index.php/upsinfo/index';
                this.fetch(url, "listdata:update", args, "get");
                return this;
            },
            getUpsInfo:function(args) {
            	var url = '/api/index.php/upsinfo/view';
                this.fetch(url, "upsInfo:get", args, "get");
                return this;
            },
            createUps:function(args){
                var url = '/api/index.php/upsinfo/create';
                this.fetch(url, "ups:create", args, "POST");
                return this;
            },
            updateUpsInfo:function(args){
                var url = '/api/index.php/upsinfo/update';
                this.fetch(url, "ups:update", args, "POST");
                return this;
            },
            deleteUps:function(args){
                var url = '/api/index.php/upsinfo/delete';
                this.fetch(url, "listitem:delete", args, "POST");
                return this;
            },
            /***************************************外控设备***************************************/
            getStationdeviceInfos: function (args) {
                var url = '/api/index.php/stationdevice/index';
                this.fetch(url, "listdata:update", args, "get");
                return this;
            },
            getStationdevice: function (args) {
                var url = '/api/index.php/stationdevice/view';
                this.fetch(url, "stationdevice:get", args, "get");
                return this;
            },
            createStationdevice: function (args) {
                var url = '/api/index.php/stationdevice/create';
                this.fetch(url, "stationdevice:create", args, "POST");
                return this;
            },
            deleteStationdevice: function (args) {
                var url = '/api/index.php/stationdevice/delete';
                this.fetch(url, "listitem:delete", args, "get");
                return this;
            },
            updateStationdevice: function (args) {
                var url = '/api/index.php/stationdevice/update';
                this.fetch(url, "stationdevice:update", args, "POST");
                return this;
            },
            /***************************************告警***************************************/
            getCautionsData: function (args,event,unalert) {
                var url = '/api/index.php/realtime/galarm';
                this.fetch(url, event||"listdata:update", args, "get",window,unalert);
                return this;
            },
            resolveCaution:function(args){
                var url = '/api/index.php/gerneralalarm/update';
                this.fetch(url, "caution:resolved", args, "post");
                return this;
            },
            /***************************************门限***************************************/
            getAlarmOptions: function (args) {//获取单个站的门限设置
                var url = '/api/index.php/alarmsiteconf';
                this.fetch(url, "alarmOptions:get", args, "get");
                return this;
            },
            openLimit:function(args){
                var url = '/api/index.php/alarmsiteconf/update?status=1';
                this.fetch(url, "limit:open", args, "get");
                return this;
            },
            closeLimit:function(args){
                var url = '/api/index.php/alarmsiteconf/update?status=2';
                this.fetch(url, "limit:close", args, "get");
                return this;
            },
            updateLimit:function(args){
                var url = '/api/index.php/alarmsiteconf/update';
                this.fetch(url, "alarmOptions:update", args, "post");
                return this;
            },
            /***************************************系统设置***************************************/
            getSyestemConfig:function(args){
                var url = 'data/systemConfig.json';
                this.fetch(url, "systemConfig:update", args, "get");
                return this;
            },
            /***************************************图表***************************************/
            getChartData: function (args) {
                console.log('chart args', args);
                var url = 'data/stationlist.json';
                this.fetch(url, "stations:update", args, "get");
                return this;
            },
            /***************************************参数***************************************/
            updateParam:function(args,event){
                var url = '/api/index.php/param';
                this.fetch(url, event||"param:update", args, "post");
                return this;
            },
            getParam:function(args,event){
                var url = '/api/index.php/param/getpara';
                this.fetch(url, event||"param:update", args, "post");
                return this;
            },
            /***************************************采集***************************************/
            collect:function(){
                Backbone.Events.trigger("listdata:refresh");
                return this;
            },
            forceCollect:function(){
                var url = '/api/index.php/uids/forcerin';
                this.fetch(url, event||"param:update", args, "post");
                return this;
            },
            /***************************************报表***************************************/
            //电池使用年限
            getByearlog:function(args){
                var url = '/api/index.php/report/byearlog';
                this.fetch(url, "listdata:update", args, "post");
                return this;
            },
            //报警
            getGerneralalarmlog:function(args){
                var url = '/api/index.php/gerneralalarm';
                this.fetch(url, "listdata:update", args, "post");
                return this;
            },

            //充放电
            getChargeOrDischarge: function(args){
                var url = '/api/index.php/report/chargeOrDischarge';
                this.fetch(url, "listdata:update", args, "get");
                return this;
            },
            //偏离趋势
            getDeviationTrend: function(args){
                var url = '/api/index.php/report/deviationTrend';
                this.fetch(url, "listdata:update", args, "get");
                return this;
            },
            //UI日志
            getUserlog:function(args){
                var url = '/api/index.php/userlog';
                this.fetch(url, "listdata:update", args, "post");
                return this;
            },
            getChart:function(args,evttype,type){
                var url = {
                    'battery':'/api/index.php/realtime/batterychart',
                    'qureyBattery':'/api/index.php/realtime/batterychart',
                    'group':'/api/index.php/realtime/group',
                    'qureyGroup':'/api/index.php/realtime/groupchart',
                    'station':'/api/index.php/realtime',
                    'qureyStation':'/api/index.php/realtime/stationchart',
                    'caution':'/api/index.php/realtime/galarmchart'
                };
                // if(args.id.split(",").length <= 1){
                //     return this;
                // }
                //this.fetch(url[type], evttype||"chart:update", args, "get");
                return this;
            },
			syncHard: function(args, ctype){
				this.fetch("http://127.0.0.1:3000/setparam?type="+ctype, null, args, "post");
			}
        }

    return API;
})
