define(["require","jquery","common"],function(e){var t=e("jquery"),n=e("common"),r={isContained:function(e,t){if(e instanceof Array&&t instanceof Array){if(e.length<t.length)return!1;var n=e.toString();console.info(n);for(var r=0,i=t.length;r<i;r++){console.info(n.indexOf(t[r]));if(n.indexOf(t[r])==-1)return!1}return!0}return!1},fetch:function(e,r,i,s,o,u,a){var f=this;e.indexOf("query")>-1&&n.loadTips.show("数据加载中..."),t.ajax({type:s||"GET",data:t.extend(!0,{},i),url:e,success:function(i){n.loadTips.close(),typeof i=="string"&&(i=t.parseJSON(i));if(!i||typeof i!="object")Backbone.Events.trigger(r,"error",i.response.message||i.response.msg);else if(i.response.code==0){a&&(i.data.callback=a);if(e=="/api/index.php/stationperson/index"){var s=[],l=JSON.parse(localStorage.getItem("userinfo")).uid,c=null;t.each(i.data.list,function(e,t){console.log(e,t);if(t.id==l){c=t;return}}),c.area=="*"?s=i.data.list:t.each(i.data.list,function(e,t){t.area!="*"&&(console.log(c.area,t.area),f.isContained(c.area.split(","),t.area.split(","))&&s.push(t))}),i.data={list:s}}console.log("start trigger",r),Backbone.Events.trigger(r,i.data,o),a&&a()}else if(i.response.code=="-100")route.navigate("login",{trigger:!0});else{Backbone.Events.trigger(r+":fail",i,o);if(u)return}},error:function(t){n.loadTips.close(),console.log(t),Backbone.Events.trigger("messager",{ret:1,massage:e,data:[]},o||window)}})},stat:function(){var e="/api/index.php/stat/info";return this.fetch(e,"stat","get"),this},login:function(e){var t="/api/index.php/login";return e.refresh?this.fetch(t,"login:box",e,"post"):this.fetch(t,"login",e,"post"),this},getMapData:function(e,t){var n="/api/index.php/map/sites";return this.fetch(n,t||"mapdata:update",e,"get"),this},getNavData:function(e,t){var n="/api/index.php/trees/getnav";return this.fetch(n,"nav:update",t,"get",this,!0,e),this},getRolesData:function(e){var t="/api/index.php/role/index";return this.fetch(t,"listdata:update",e,"get"),this},getPersonalsData:function(e){var t="/api/index.php/stationperson/index";return this.fetch(t,"listdata:update",e,"get"),this},getPersonalInfo:function(e){var t="/api/index.php/stationperson/view";return this.fetch(t,"personalInfo:get",e,"get"),this},updatePersonalInfo:function(e){var t="/api/index.php/stationperson/update";return this.fetch(t,"personal:update",e,"post"),this},createPersonal:function(e){var t="/api/index.php/stationperson/create";return this.fetch(t,"personal:create",e,"post"),this},deletePersonal:function(e){var t="/api/index.php/stationperson/delete";return this.fetch(t,"listitem:delete",e,"get"),this},getTreeInfo:function(e){var t="/api/index.php/trees";return this.fetch(t,"tree:get",e,"get"),this},updateTree:function(e){var t="/api/index.php/trees/update";return this.fetch(t,"tree:update",e,"get"),this},createTree:function(e){var t="/api/index.php/trees/create";return this.fetch(t,"tree:create",e,"get"),this},deleteTree:function(e){var t="/api/index.php/trees/delete";return this.fetch(t,"tree:delete",e,"get"),this},getStationRealTimeData:function(e){var t="/api/index.php/realtime";return this.fetch(t,"stationdata:get",e,"get"),this},getAboutInfo:function(e){var t="/api/index.php/bmsinfo";return this.fetch(t,"abouts:get",e,"get"),this},getStationHistoryData:function(e){var t="/api/index.php/query";return this.fetch(t,"stationdata:get",e,"get"),this},getSystemAlarm:function(e){var t="/api/index.php/systemalarm";return this.fetch(t,"listdata:update",e,"get"),this},getGroupHistoryData:function(e){var t="/api/index.php/query/groupmodule";return this.fetch(t,"listdata:update",e,"post"),this},getBatteryHistoryData:function(e){var t="/api/index.php/query/batterymodule";return this.fetch(t,"listdata:update",e,"post"),this},getLinkingStationNum:function(e,t){var n="/api/index.php/stat";return this.fetch(n,t||"linknum:get",e,"get"),this},createStation:function(e,t){var n="/api/index.php/sites/create";return this.fetch(n,t||"stationdata:create",e,"POST"),this},updateStation:function(e){var t="/api/index.php/sites/update";return this.fetch(t,"stationdata:update",e,"POST"),this},deleteStation:function(e){var t="/api/index.php/sites/delete";return this.fetch(t,"stationdata:delete",e,"POST"),this},getNewStationData:function(e){var t="/api/index.php/sites/newstations";return this.fetch(t,"listdata:update",e,"get"),this},getIRCollectData:function(e){var t="/api/index.php/ircollect";return this.fetch(t,"listdata:update",e,"get"),this},updateCollect:function(e){var t="/api/index.php/ircollect/update";return this.fetch(t,"rCollect:start",e,"post"),this},checkStation:function(e){var t="/api/index.php/sites/check";return this.fetch(t,"station:check",e,"get"),this},getStationSlectorList:function(e){var t="/api/index.php/sites/suggest";return this.fetch(t,"stationSelectorList:get",e,"get"),this},getStationInfo:function(e){var t="/api/index.php/map/sitesinfo";return this.fetch(t,"stationinfo:update",e,"get"),this},getStationsInfo:function(e){var t="/api/index.php/sites";return this.fetch(t,"listdata:update",e,"post"),this},getStationEditInfo:function(e){var t="/api/index.php/sites/view";return this.fetch(t,"stationinfo:foredit:update",e,"get"),this},getStationOptionEditInfo:function(e){var t="/api/index.php/stationpara/view";return this.fetch(t,"stationoption:get",e,"get"),this},getSationOptionsData:function(e){var t="/api/index.php/stationpara/index";return this.fetch(t,"listdata:update",e,"get"),this},updateStationOption:function(e){var t="/api/index.php/stationpara/update";return this.fetch(t,"stationoption:update",e,"POST"),this},getGroupsData:function(e){var t="/api/index.php/realtime/groupmodule";return this.fetch(t,"listdata:update",e,"get"),this},getGroupOptionData:function(e){var t="/api/index.php/grouppara/index";return this.fetch(t,"listdata:update",e,"get"),this},getGroupOption:function(e){var t="/api/index.php/grouppara/view";return this.fetch(t,"groupoption:get",e,"get"),this},updateGroupOption:function(e){var t="/api/index.php/grouppara/update";return this.fetch(t,"groupoption:update",e,"post"),this},getMessagesData:function(e){var t="/api/index.php/alarmset/config";return this.fetch(t,"listdata:update",e,"get"),this},getMessageInfo:function(e){var t="/api/index.php/alarmset/view";return this.fetch(t,"messageInfo:get",e,"get"),this},updateMessage:function(e){var t="/api/index.php/alarmset/updateMsg";return this.fetch(t,"message:update",e,"post"),this},createMessage:function(e){var t="/api/index.php/alarmset/create";return this.fetch(t,"message:create",e,"post"),this},deleteMessage:function(e){var t="/api/index.php/alarmset/delete";return this.fetch(t,"listitem:delete",e,"get"),this},getBatterysRealTimeData:function(e){var t="/api/index.php/realtime/batterymodule";return this.fetch(t,"listdata:update",e,"get"),this},getBatteryInfosData:function(e){var t="/api/index.php/batteryinfo/index";return this.fetch(t,"listdata:update",e,"get"),this},getBatteryInfo:function(e){var t="/api/index.php/batteryinfo/view";return this.fetch(t,"batteryInfo:get",e,"get"),this},getBatteryOptionsData:function(e){var t="/api/index.php/batterypara";return this.fetch(t,"listdata:update",e,"get"),this},getBatteryOption:function(e){var t="/api/index.php/batterypara/view";return this.fetch(t,"batteryoption:get",e,"get"),this},updateBatteryOption:function(e){var t="/api/index.php/batterypara/update";return this.fetch(t,"batteryoption:update",e,"POST"),this},createBattery:function(e,t){var n="/api/index.php/batteryinfo/create";return this.fetch(n,t||"battery:create",e,"POST"),this},updateBatteryInfo:function(e){var t="/api/index.php/batteryinfo/update";return this.fetch(t,"batteryInfo:update",e,"POST"),this},deleteBattery:function(e){var t="/api/index.php/batteryinfo/delete";return this.fetch(t,"listitem:delete",e,"POST"),this},getBMSInfosData:function(e){var t="/api/index.php/bmsinfo";return this.fetch(t,"listdata:update",e,"get"),this},getBMSInfo:function(e){var t="/api/index.php/bmsinfo/view";return this.fetch(t,"bmsInfo:get",e,"get"),this},updateBMSInfo:function(e){var t="/api/index.php/bmsinfo/update";return this.fetch(t,"bms:update",e,"post"),this},createBMS:function(e){var t="/api/index.php/bmsinfo/create";return this.fetch(t,"bms:create",e,"post"),this},deleteBMS:function(e){var t="/api/index.php/bmsinfo/delete";return this.fetch(t,"listitem:delete",e,"get"),this},getCompanyInfosData:function(e){var t="/api/index.php/companyinfo";return this.fetch(t,"listdata:update",e,"get"),this},getCompanyInfo:function(e){var t="/api/index.php/companyinfo/view";return this.fetch(t,"companyInfo:get",e,"get"),this},updateCompanyInfo:function(e){var t="/api/index.php/companyinfo/update";return this.fetch(t,"company:update",e,"post"),this},createCompany:function(e){var t="/api/index.php/companyinfo/create";return this.fetch(t,"company:create",e,"post"),this},deleteCompany:function(e){var t="/api/index.php/companyinfo/delete";return this.fetch(t,"listitem:delete",e,"get"),this},getUpsInfosData:function(e){var t="/api/index.php/upsinfo/index";return this.fetch(t,"listdata:update",e,"get"),this},getUpsInfo:function(e){var t="/api/index.php/upsinfo/view";return this.fetch(t,"upsInfo:get",e,"get"),this},createUps:function(e){var t="/api/index.php/upsinfo/create";return this.fetch(t,"ups:create",e,"POST"),this},updateUpsInfo:function(e){var t="/api/index.php/upsinfo/update";return this.fetch(t,"ups:update",e,"POST"),this},deleteUps:function(e){var t="/api/index.php/upsinfo/delete";return this.fetch(t,"listitem:delete",e,"POST"),this},getStationdeviceInfos:function(e){var t="/api/index.php/stationdevice/index";return this.fetch(t,"listdata:update",e,"get"),this},getStationdevice:function(e){var t="/api/index.php/stationdevice/view";return this.fetch(t,"stationdevice:get",e,"get"),this},createStationdevice:function(e){var t="/api/index.php/stationdevice/create";return this.fetch(t,"stationdevice:create",e,"POST"),this},deleteStationdevice:function(e){var t="/api/index.php/stationdevice/delete";return this.fetch(t,"listitem:delete",e,"get"),this},updateStationdevice:function(e){var t="/api/index.php/stationdevice/update";return this.fetch(t,"stationdevice:update",e,"POST"),this},getCautionsData:function(e,t,n){var r="/api/index.php/realtime/galarm";return this.fetch(r,t||"listdata:update",e,"get",window,n),this},resolveCaution:function(e){var t="/api/index.php/gerneralalarm/update";return this.fetch(t,"caution:resolved",e,"post"),this},getAlarmOptions:function(e){var t="/api/index.php/alarmsiteconf";return this.fetch(t,"alarmOptions:get",e,"get"),this},openLimit:function(e){var t="/api/index.php/alarmsiteconf/update?status=1";return this.fetch(t,"limit:open",e,"get"),this},closeLimit:function(e){var t="/api/index.php/alarmsiteconf/update?status=2";return this.fetch(t,"limit:close",e,"get"),this},updateLimit:function(e){var t="/api/index.php/alarmsiteconf/update";return this.fetch(t,"alarmOptions:update",e,"post"),this},getSyestemConfig:function(e){var t="data/systemConfig.json";return this.fetch(t,"systemConfig:update",e,"get"),this},getChartData:function(e){console.log("chart args",e);var t="data/stationlist.json";return this.fetch(t,"stations:update",e,"get"),this},updateParam:function(e,t){var n="/api/index.php/param";return this.fetch(n,t||"param:update",e,"post"),this},getParam:function(e,t){var n="/api/index.php/param/getpara";return this.fetch(n,t||"param:update",e,"post"),this},collect:function(){return Backbone.Events.trigger("listdata:refresh"),this},forceCollect:function(){var e="/api/index.php/uids/forcerin";return this.fetch(e,event||"param:update",args,"post"),this},getByearlog:function(e){var t="/api/index.php/report/byearlog";return this.fetch(t,"listdata:update",e,"post"),this},getGerneralalarmlog:function(e){var t="/api/index.php/gerneralalarm";return this.fetch(t,"listdata:update",e,"post"),this},getChargeOrDischarge:function(e){var t="/api/index.php/report/chargeOrDischarge";return this.fetch(t,"listdata:update",e,"get"),this},getDeviationTrend:function(e){var t="/api/index.php/report/deviationTrend";return this.fetch(t,"listdata:update",e,"get"),this},getUserlog:function(e){var t="/api/index.php/userlog";return this.fetch(t,"listdata:update",e,"post"),this},getChart:function(e,t,n){var r={battery:"/api/index.php/realtime/batterychart",qureyBattery:"/api/index.php/realtime/batterychart",group:"/api/index.php/realtime/group",qureyGroup:"/api/index.php/realtime/groupchart",station:"/api/index.php/realtime",qureyStation:"/api/index.php/realtime/stationchart",caution:"/api/index.php/realtime/galarmchart"};return this},syncHard:function(e,t){this.fetch(location.protocol+"//"+location.host+":3000/setparam?type="+t,null,e,"post")}};return r});