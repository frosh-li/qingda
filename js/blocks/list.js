define(['require','api','blocks/nav','stationsinfoDialog','context','ui','common','table','fixedHeader'],function(require,API,nav,stationInfoDialog,context,ui,common){
    var listView = null,
        pageView = null,
        _listType,_sub,_sys,
        overFlag = false,
        dataTableDefaultOption = {
            "paging": false,
            "searching": false,
            "order": [ 1, 'asc' ],
            "autoWidth": true,
            "scrollCollapse":true,
            "language": {
                "emptyTable": "暂无数据"
            },
            "scroller": {
                "rowHeight": 'auto'
            },
            "fixedHeadere": {
                header:true
            },
            "fnDrawCallback": function ( oSettings ) {
                /* Need to redo the counters if filtered or sorted */
                if ( oSettings.bSorted || oSettings.bFiltered ) {
                    for ( var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ ) {
                        $('td:eq(0)', oSettings.aoData[ oSettings.aiDisplay[i] ].nTr ).html('<label class="index">'+ (i+1)+'</label>' );
                    }
                }
            },
            "info":false
        },
        listConfig = {
            defaultConfig:{
                extObj : {
                    data:null,
                    el:'#list',
                    events:{
                        "click .dataTable tr":"selectRow",
                        "click .show-info":"openStationInfoDialog",
                        "mouseover .dataTable tr":"inRow",
                        "mouseout .dataTable tr":"inRow"
                    },
                    initialize:function(data){
                        var _this = this;
                        _this.destroy();
                        _this.captureEvt();
                        
                    },
                    checkAllRows: function(){
                        var _this = this;
                        setTimeout(function(){
                            var allRows = $('.dataTable tr', _this.el);
                            console.log('check all rows', allRows)
                            for(var i = 0 ; i < allRows.length; i++){
                                _this.changeRowClass($(allRows[i]),'selected')
                            }
                            _this.triggerSelectEvent();
                        },0)
                        
                        
                    },
                    captureEvt:function(){
                        var _this = this;
                        _this.listenTo(Backbone.Events,"listdata:update stationdata:get",function(data){
                            if(!$(_this.el).length || !$(_this.el).is(":visible")){
                                return;
                            }

                            _this.data = data.list||data||_this.data;
                            _this.render();
                            overFlag = true;
                        });
                        _this.listenTo(Backbone.Events,"listdata:refresh",function(){
                            _this.refresh();
                        });
                        _this.listenTo(Backbone.Events,"listdata:update:fail stationdata:get:fail",function(data){
                            if(data.response.code == "-1"){
                                overFlag = true;
                                _this.data = [];
                                _this.render();
                            }
                        });
                        _this.listenTo(Backbone.Events,"listitem:delete",function(data){
                            alert("删除成功");
                            _this.refresh();
                        });
                        /*_this.listenTo(Backbone.Events,"pageType:change",function(type){
                            if(!$(_this.el).length || !$(_this.el).is(":visible")){
                                return;
                            }
                            initPage(type);
                            console.log("pageType:change");
                            _this.refresh();
                        });*/
                        _this.listenTo(Backbone.Events,"curstation:change",function(data){
                            _this.ids = null;
                            if(!$(_this.el).length || !$(_this.el).is(":visible")){
                                return;
                            }
                            var navData = _this.getNavData();
                            if(typeof navData == 'undefined' || !navData || !navData.ids.length){
                                _this.data = [];
                                _this.render();
                            }else{
                                _this.refresh();
                            }
                        });

                        _this.listenTo(Backbone.Events,"colsChange",function(data){
                            //initPage(_listType,_sub);
                            _this.refresh();
                        });

                        _this.extEvent && _this.extEvent();
                    },
                    openStationInfoDialog:function(){
                        stationInfoDialog.show();
                    },
                    getCols:function(type){
                        var customCols = common.cookie.getCookie(type+'Cols'),allCols = context.getListCols(type),retCols=[],width=0;
                        if(customCols){
                            customCols = customCols.split(',');
                            retCols = common.filterArray(allCols,customCols,'data');
                        }else{
                            retCols = allCols;
                        }
                        $.each(retCols,function(i,col){
                            width+=parseInt(col.width)||0;
                        })
                        return {data:retCols,width:width};
                    },
                    inRow:function(evt){
                        var _this = this;
                        _this.changeRowClass($(evt.currentTarget),'highlight');
                    },/*
                    outRow:function(evt){
                        var _this = this,
                            $tr = $(evt.currentTarget),
                            trIndex = $tr.index(),
                            $tbodys = $("tbody",_this.el),
                            data = _this.getRowData(evt.currentTarget);

                        $tbodys.each(function(i,tbody){
                             var $tbody = $(tbody);
                             $tbody.find("tr").eq(trIndex).toggleClass('highlight');
                         })
                    },*/
                    changeRowClass:function($tr,className){
                        var _this = this,
                            trIndex = $tr.index(),
                            $tbodys = $("tbody",_this.el),
                            data = _this.getRowData($tr);

                        // console.log(trIndex, data);
                        if(!data || !data.data){
                            return this;
                        }
                        console.log(data, trIndex);
                        $tbodys.each(function(i,tbody){
                            var $tbody = $(tbody);
                            $tbody.find("tr").eq(trIndex).toggleClass(className);
                        })

                        return this;
                    },
                    selectRow:function(evt){
                        var _this = this;
                        _this.changeRowClass($(evt.currentTarget),'selected').triggerSelectEvent();
                    },
                    destroy:function(){
                        this.stopListening();
                        this.destoryPlugin();
                        this.clearTables();
                    },
                    triggerSelectEvent:function(){
                        //整理数据发送选择行
                        var _this = this,
                            selectedData = _this.listPlugin[0].rows('.selected').data(),
                            ids = [];
                        $.each(selectedData,function(i,d){
                            ids.push(d.sn_key);
                        });
                        Backbone.Events.trigger('row:select',ids);
                    },
                    getRowData:function(tr){
                        var ret = {};
                        $.each(this.listPlugin,function(i,p){
                            ret.data = p.row(tr).data();
                            if(ret.data){
                                ret.index = p.row(tr).index();
                                return false;
                            }
                        })
                        return ret;
                    },
                    resetScrollBar:function(){},
                    refresh:function(){
                        this.destoryPlugin();
                        this.fetchData();
                    },
                    fetchData:function(_param){
                        API.getStationRealTimeData(_param);
                    },
                    getParam:function(){
                        var curstation = context.getCurStations(),
                            listType = context.getListType();

                        return {
                            listType:listType
                        };
                    },
                    getColumnDefs:function(){
                        return [
                            {
                                targets:0,
                                render:function(data, type, row){
                                    return '<label class="show-info">'+ data +'</label>';
                                }
                            }
                        ]
                    },
                    changeListBottom:function(type){
                        //TODO:列表底部元素展示隐藏
                    },
                    destoryPlugin:function(){
                        this.clearTables();
                        this.listPlugin = [];
                        return this;
                    },
                    clearTables:function(){
                        try{
                            if ( $.fn.dataTable.isDataTable( '#auto table' )){
                                this.listPlugin[0].clear();
                                this.listPlugin[0].destroy();
                            }
                            $("#list").html($("#defaultListTpl").html())
                        }catch(e){}
                    },
                    render:function(){},
                    onAddStation:function(){}
                }
            },
            station:{
                extObj:{
                    getNavData:function(){
                        return nav.getSites();
                    },
                    fetchData:function(){
                        var _param = {};
                        var navData = nav.getSites();
                        var ids;

                        if(this.ids && this.ids.sid){
                            ids = this.ids.sid;
                        }else{
                            ids = navData.ids.join(",");
                        }

                        $.extend(_param,{id:ids});
                        API.getStationRealTimeData(_param);
                    },
                    render:function(){
                        var _this = this,colums = _this.getCols('station');
                        _this.destoryPlugin();
                        require(["fixedColumn"],function(){
                            if(colums.width+580>$("#list").width()){
                                dataTableDefaultOption.fixedColumns = {leftColumns:3};
                            }else{
                                try{
                                    delete colums.data[colums.data.length-1].width;
                                }catch(e){}
                            }
                            _this.listPlugin.push($('#auto table').DataTable( $.extend(true,{},dataTableDefaultOption,{
                                "data": _this.data,
                                "scrollX":ui.getListWidth(),
                                "scrollY":ui.getListHeight(),
                                "columns": [
                                    { "data": "sid", "title":"序号",width:50},
                                    { "data": "sid","title":"站号",width:50 },
                                    { "data": "record_time",title:"时间",width:180 }
                                ].concat(colums.data)
                            })));
                            _this.checkAllRows();

                            //_this.resetScrollBar();
                        })
                        //this.clearTables();
                        //ui.resizeAutoListWidth();

                        return this;
                    }
                }
            },
            battery:{
                extObj:{
                    stations:null,
                    prevIds:[],
                    curStation:'',
                    el:$('#dataItem'),
                    events:{
                        'click #page .prev':'onPrev',
                        'click #page .next':'onNext'
                    },
                    onNext:function(){
                        this.nextStation();
                        this.fetchData();
                    },
                    onPrev:function(){
                        this.prevStation();
                        this.fetchData();
                    },
                    nextStation:function(){
                        if(!this.stations.ids.length){return}
                        this.curStation && this.prevIds.push(this.curStation);
                        this.curStation = this.stations.ids.shift();
                    },
                    prevStation:function(){
                        if(!this.prevIds.length){return}
                        this.curStation && this.stations.ids.unshift(this.curStation);
                        this.curStation = this.prevIds.pop();
                    },
                    updatePageView:function(){
                        $("#page .prev,#page .next").hide();
                        if(this.prevIds.length>0){
                            $("#page .prev").show();
                        }
                        if(this.stations.ids.length>0){
                            $("#page .next").show();
                        }
                    },
                    getBatterys:function(){
                        var stations = this.stations,
                            curStation = this.curStation;
                        if(!stations || (stations.ids.length == 0&&this.prevIds.length==0)){
                            $("#page .cur").html('当前站点：全部');
                            $("#page .next,#page .prev").hide();
                            return '';
                        }else{
                            var batteryId = nav.getBatterys(curStation);
                            $("#page .cur").html('当前站点：'+stations.map[curStation].title);
                            this.updatePageView();
                            return batteryId.ids.join(',');
                        }
                    },
                    updateStations:function(){
                        this.stations = nav.getSites();
                        this.prevIds = [];
                        this.curStation = '';
                        return this;
                    },
                    refresh:function(){
                        this.updateStations();
                        if(this.curStation && this.curStation == this.stations[0]){
                            return;
                        }
                        this.nextStation();
                        this.fetchData();
                    },
                    getNavData:function(){
                        return nav.getBatterys(this.curStation);
                    },
                    fetchData:function(){
                        var _param = {};
                        this.destoryPlugin();
                        $.extend(_param,{
                            id:this.getBatterys()
                        });
                        API.getBatterysRealTimeData(_param);
                    },
                    extEvent:function(){
                        var _this = this;
                        _this.listenTo(Backbone.Events,"batteryColsChange",function(data){
                            _this.refresh();
                        });
                    },
                    render:function(){
                        var _this = this,colums = _this.getCols('battery');
                        this.destoryPlugin();
                        require(["fixedColumn"],function(){
                            if(colums.width+780>$("#list").width()){
                                dataTableDefaultOption.fixedColumns = {leftColumns:5};
                            }else{
                                try{
                                    delete colums.data[colums.data.length-1].width;
                                }catch(e){}
                            }
                            _this.listPlugin.push($('#auto table').DataTable( $.extend(true,{
                                "data": _this.data,
                                "scrollX":ui.getListWidth(),
                                "scrollY":ui.getListHeight(),
                                "scrollX":true,
                                "columns": [
                                    { "data": "bid",title:"序号",width:50 },
                                    { "data": "sid",title:"站号",width:50 },
                                    { "data": "gid",title:"组号",width:50 },
                                    { "data": "mid",title:"模块号",width:80 },
                                    { "data": "bid",title:"电池号",width:80  }
                                ].concat(colums.data)
                            },dataTableDefaultOption)));
                            _this.checkAllRows();
                        })
;
                        return this;
                    }
                }
            },
            group:{
                extObj:{
                    getNavData:function(){
                        return nav.getGroups();
                    },
                    fetchData:function(){
                        var _param = {};
                        var navData = nav.getGroups();

                        $.extend(_param,{id:navData.ids.join(",")});
                        API.getGroupsData(_param);
                    },
                    extEvent:function(){
                        var _this = this;
                        _this.listenTo(Backbone.Events,"groupColsChange",function(data){
                            _this.refresh();
                        });
                    },
                    render:function(){
                        var _this = this,colums = _this.getCols('group');
                        _this.destoryPlugin();
                        require(["fixedColumn"],function(){
                            if(colums.width+530>$("#list").width()){
                                dataTableDefaultOption.fixedColumns = {leftColumns:3};
                            }else{
                                try{
                                    delete colums.data[colums.data.length-1].width;
                                }catch(e){}
                            }
                            _this.listPlugin.push($('#auto table').DataTable( $.extend(true,{
                                "data": _this.data,
                                "scrollX":ui.getListWidth(),
                                "scrollY":ui.getListHeight(),
                                "columns": [
                                    { "data": "sid",title:"序号",width:50 },
                                    { "data": "sid",title:"站号",width:50 },
                                    { "data": "gid",title:"组号",width:50 }
                                ].concat(colums.data)
                            },dataTableDefaultOption)));
                        })
                        _this.checkAllRows();
                        return this;
                    }
                }
            },
            caution:{
                extObj:{
                    getNavData:function(){
                        return nav.getSites();
                    },
                    fetchData:function(_param){
                        var _param = {};
                        var navData = nav.getSites();

                        $.extend(_param,{id:navData.ids.join(",")});
                        API.getCautionsData(_param);
                    },
                    events:{
                        "click .resolveBtn":"resove"
                    },
                    resove:function(e){
                        ui.showUnsolveDialog({id:$(e.currentTarget).attr('pid'),suggestion:$(e.currentTarget).attr('suggestion')});
                    },
                    render:function(){
                        this.destoryPlugin();
                        ui.resizeAutoListWidth();
                        this.listPlugin.push($('#auto table').DataTable( $.extend(true,{
                            "data": this.data,
                            "scrollX":ui.getListHeight(),
                            "scrollY":ui.getListHeight(),
                            "columns": [
                                { "data": "alarm_sn",title:"序号" },
                                { "data": "alarm_para1_value",title:"站名" },
                                { "data": "alarm_para1_name",title:"站号" },
                                { "data": "alarm_para2_name",title:"组号" },//组序列号
                                { "data": "alarm_para3_name",title:"模块号" },
                                { "data": "alarm_occur_time",title:"时间" },
                                { "data": "alarm_content",title:"警情内容" },
                                { "data": "alarm_para1_value",title:"数值" },
                                { "data": "alarm_suggestion",title:"建议处理方式" },
                                {
                                    "data": "alarm_sn",
                                    title:"处理连接",
                                    render: function (data,type,itemData) {
                                        return _.template('<a class="resolveBtn" pid="<%=id%>" suggestion="<%=suggestion%}">未处理</a>')({
                                            id:data,
                                            suggestion:itemData.alarm_suggestion
                                        });
                                    }
                                },
                                { "data": "alarm_process_and_memo",title:"处理过程、时间、管理员" }
                            ]
                        },dataTableDefaultOption)));
                        _this.checkAllRows();
                        return this;
                    }
                }
            },
            "stationInfo_stationSituation":{
                extObj:{
                    events:{
                        "click .list-edit-btn":"onEdit",
                        "click .list-del-btn":"onDel",
                        "click .list-validate-btn":"onValidate",
                        "mouseover .dataTable tr":"inRow",
                        "mouseout .dataTable tr":"inRow"
                    },
                    onEdit:function(e){
                        ui.showStationEditDialog($(e.currentTarget).attr('pid'));
                    },
                    onDel:function(e){
                        if(confirm("是否确定删除此站点")){
                            API.deleteStation({
                                id:$(e.currentTarget).attr('pid')
                            });
                        }
                    },
                    onValidate:function(e){
                        common.loadTips.show("校验中，请稍等...");
                        API.checkStation({
                            id:$(e.currentTarget).attr('pid'),
                            serial_number:$(e.currentTarget).attr('sn')
                        });
                    },
                    extEvent:function(){
                        var _this = this;
                        this.listenTo(Backbone.Events,"stationdata:delete",function(data){
                            alert("删除成功");
                            _this.refresh();
                        });
                        this.listenTo(Backbone.Events,"station:check",function(data){
                            alert("校验成功");
                            common.loadTips.close();
                            _this.refresh();
                        });
                        this.listenTo(Backbone.Events,"station:check:fail",function(data){
                            common.loadTips.close();
                            _this.refresh();
                        });
                    },
                    fetchData:function(_param){
                        API.getStationsInfo(_param);
                    },
                    render:function(){
                        var _this = this;
                        _this.destoryPlugin();
                        require(["fixedColumn"],function() {
                            _this.listPlugin.push($('#auto table').DataTable( $.extend(true,{
                                "data": _this.data,
                                "language": {
                                    "emptyTable": "站点数据为空"
                                },
                                "scrollX": ui.getListHeight(),
                                "scrollY": ui.getListHeight(),
                                "fixedColumns": {rightColumns: 1},
                                "columns": [
                                    { "data": "sid",title:"序号",width:50},
                                    { "data": "sid",title:"站号",width:100  },
                                    { "data": "serial_number",title:"物理地址",width:50  },
                                    { "data": "site_name",title:"站点简称",width:100  },
                                    { "data": "StationFullChineseName",title:"站点全称",width:100  },
                                    { "data": "site_location",title:"站点地址",width:100  },
                                    { "data": "site_property",title:"站点性质",width:150  },
                                    { "data": "aid",title:"隶属区域",width:150  },
                                    { "data": "emergency_person",title:"紧急联系人姓名",width:150  },
                                    { "data": "emergency_phone",title:"紧急联系人手机",width:250  },
                                    { "data": "groups",title:"电池组数",width:100  },
                                    { "data": "batteries",title:"每组电池数",width:250  },
                                    { "data": "postal_code",title:"邮政编码",width:250  },
                                    { "data": "site_latitude",title:"站点纬度",width:250  },
                                    { "data": "site_longitude",title:"站点经度",width:250  },
                                    { "data": "ipaddress",title:"IP地址",width:250  },
                                    { "data": "ipaddress_method",title:"控制器IP地址或方式",width:150  },
                                    { "data": "site_control_type",title:"站点控制器型号",width:200  },
                                    { "data": "bms_install_date",title:"BMS系统安装日期",width:150  },
                                    { "data": "group_collect_type",title:"组电流采集器型号",width:150  },
                                    { "data": "group_collect_num",title:"组电流采集器数量",width:150  },
                                    { "data": "inductor_brand",title:"互感器品牌",width:150  },
                                    { "data": "group_collect_install_type",title:"组电流采集器安装模式",width:150  },
                                    { "data": "battery_collect_type",title:"电池数据采集器型号",width:150  },
                                    { "data": "battery_collect_num",title:"电池数据采集器数量",width:150  },
                                    { "data": "humiture_type",title:"环境温湿度方式",width:150  },
                                    {
                                        data:"id",
                                        render: function (data,type,itemData) {
                                            var tpl='';
                                            if(itemData.is_checked == "1"){
                                                tpl = '<div style="width:240px">'+$("#validateSuccess").html()+$("#editBtn").html()+$("#delBtn").html()+'</div>';
                                            }else{
                                                tpl = '<div style="width:240px">'+$("#validateBtn").html()+$("#editBtn").html()+$("#delBtn").html()+'</div>';
                                            }
                                            return _.template(tpl)({
                                                id:data,
                                                sn:itemData.serial_number
                                            });
                                        }
                                    }
                                ]
                            },dataTableDefaultOption)));
                            _this.checkAllRows();
                        })
                        return this;
                    }
                }
            },
            "stationInfo_batterys":{
                extObj:{
                    events:{
                        "click .list-edit-btn":"onEdit",
                        "click .list-del-btn":"onDel",
                        "mouseover .dataTable tr":"inRow",
                        "mouseout .dataTable tr":"inRow"
                    },
                    onEdit:function(e){
                        ui.showBatteryEditDialog($(e.currentTarget).attr('pid'));
                    },
                    onDel:function(e){
                        if(confirm("是否确定删除此电池")){
                            API.deleteBattery({
                                id:$(e.currentTarget).attr('pid')
                            });
                        }
                    },
                    fetchData:function(_param){
                        API.getBatteryInfosData(_param);
                    },
                    render:function(){
                        var _this = this;
                        _this.destoryPlugin();
                        require(["fixedColumn"],function() {
                            _this.listPlugin.push($('#auto table').DataTable( $.extend(true,{
                                "data": _this.data,
                                "language": {
                                    "emptyTable": "电池数据为空"
                                },
                                "scrollX": ui.getListHeight(),
                                "scrollY": ui.getListHeight(),
                                "fixedColumns": {rightColumns: 1},
                                "columns": [
                                    { "data": "sid",title:"序号",width:50 },
                                    { "data": "sid",title:"站号",width:100  },
                                    { "data": "site_name",title:"站点简称",width:100  },
                                    { "data": "battery_factory",title:"生产厂家",width:150  },
                                    { "data": "battery_num",title:"电池型号",width:150  },
                                    { "data": "battery_num",title:"生产日期",width:150  },
                                    { "data": "battery_voltage",title:"标称电压（V）",width:150  },
                                    { "data": "battery_oum",title:"标称内阻（毫欧）",width:150  },
                                    { "data": "battery_max_current",title:"最大充电电流（A）",width:150  },
                                    { "data": "battery_float_up",title:"浮充电压上限（V）",width:150  },
                                    { "data": "battery_float_dow",title:"电池浮充电压下限（V）",width:150  },
                                    { "data": "battery_discharge_down",title:"放电电压下限（V）",width:150  },
                                    { "data": "battery_scrap_date",title:"强制报废日期",width:150  },
                                    { "data": "battery_life",title:"设计寿命（年）",width:150  },
                                    { "data": "battery_column_type",title:"电池级柱类型",width:150  },
                                    { "data": "battery_temperature",title:"温湿度要求（度）",width:150  },
                                    { "data": "battery_humidity",title:"湿度要求（%）",width:150  },
                                    { "data": "battery_type",title:"电池种类",width:150  },
                                    { "data": "battery_factory_phone",title:"电池厂家联系电话",width:150  },
                                    {
                                        data:"id",
                                        render: function (data) {
                                            return _.template('<div style="width:160px">'+$("#editBtn").html()+$("#delBtn").html()+'</div>')({
                                                id:data
                                            });
                                        }
                                    }
                                ]
                            },dataTableDefaultOption)));
                            _this.checkAllRows();
                        })
                        return this;
                    }
                }
            },
            //UPS信息表
            "stationInfo_upsInfo":{
                extObj:{
                    events:{
                        "click .list-edit-btn":"onEdit",
                        "click .list-del-btn":"onDel",
                        "mouseover .dataTable tr":"inRow",
                        "mouseout .dataTable tr":"inRow"
                    },
                    onEdit:function(e){
                        ui.showUPSEditDialog($(e.currentTarget).attr('pid'));
                    },
                    onDel:function(e){
                        if(confirm("是否确定删除此UPS数据")){
                            API.deleteUps({
                                id:$(e.currentTarget).attr('pid')
                            });
                        }
                    },
                    fetchData:function(){
                        API.getUpsInfosData();
                    },
                    render:function(){
                        var _this = this;
                        _this.destoryPlugin();
                        $('#auto').width('100%');
                        require(["fixedColumn"],function() {
                            _this.listPlugin.push($('#auto table').DataTable( $.extend(true,{
                                "data": _this.data,
                                "language": {
                                    "emptyTable": "UPS数据为空"
                                },
                                "scrollX": ui.getListHeight(),
                                "scrollY": ui.getListHeight(),
                                "fixedColumns": {rightColumns: 1},
                                "columns": [
                                    { "data": "id",title:"序号",width:50 },
                                    { "data": "site_name",title:"站点",width:250 },
                                    { "data": "ups_factory",title:"生产厂家",width:250 },
                                    { "data": "ups_type",title:"型号",width:100 },
                                    { "data": "ups_create_date",title:"生产日期",width:150 },
                                    { "data": "ups_install_date",title:"安装日期",width:150 },
                                    { "data": "ups_power",title:"功率（W）",width:150 },
                                    { "data": "redundancy_num",title:"冗余数量(台)",width:150 },
                                    { "data": "floting_charge",title:"浮充电压（V）",width:150 },
                                    { "data": "ups_vdc",title:"电压范围(V)",width:150 },
                                    { "data": "ups_reserve_hour",title:"额定候备时间（小时）",width:250 },
                                    { "data": "ups_charge_mode",title:"充电方式",width:100 },
                                    { "data": "ups_max_charge",title:"最大充电电流（A）",width:150 },
                                    { "data": "ups_max_discharge",title:"最大放电电流（A）",width:150 },
                                    { "data": "ups_period_days",title:"规定维护周期（天）",width:150 },
                                    { "data": "ups_discharge_time",title:"维护放电时长（分钟）",width:150 },
                                    { "data": "ups_discharge_capacity",title:"维护放电容量（%）",width:150 },
                                    { "data": "ups_maintain_date",title:"维护到期日",width:150 },
                                    { "data": "ups_vender_phone",title:"厂家联系电话",width:120 },
                                    { "data": "ups_service",title:"服务商名称",width:120 },
                                    { "data": "ups_service_phone",title:"服务商电话",width:100 },
                                    {
                                        data:"id",
                                        render: function (data) {
                                            return _.template('<div style="width:80px">'+$("#editBtn").html()/*+$("#delBtn").html()*/+'</div>')({
                                                id:data
                                            });
                                        }
                                    }
                                ]
                            },dataTableDefaultOption)));
                            _this.checkAllRows();
                        })
                        return this;
                    }
                }
            },
            //BMS信息表
            "stationInfo_monitorSeller":{
                extObj:{
                    events:{
                        "click .list-edit-btn":"onEdit",
                        "click .list-del-btn":"onDel",
                        "mouseover .dataTable tr":"inRow",
                        "mouseout .dataTable tr":"inRow"
                    },
                    onEdit:function(e){
                        ui.showBMSEditDialog($(e.currentTarget).attr('pid'));
                    },
                    onDel:function(e){
                        if(confirm("是否确定删除此UPS数据")){
                            API.deleteBMS({
                                id:$(e.currentTarget).attr('pid')
                            });
                        }
                    },
                    fetchData:function(){
                        API.getBMSInfosData();
                    },
                    render:function(){
                        var _this = this;
                        _this.destoryPlugin();
                        $('#auto').width('100%');
                        require(["fixedColumn"],function() {
                            _this.listPlugin.push($('#auto table').DataTable( $.extend(true,{
                                "data": _this.data,
                                "scrollX":ui.getListHeight(),
                                "scrollY":ui.getListHeight(),
                                "fixedColumns": {rightColumns: 1},
                                "columns": [
                                    { "data": "id",title:"序号",width:50 },
                                    {data:'bms_company',title:'BMS设备生产厂家名称',width:200},
                                    {data:'bms_device_addr',title:'BMS设备生产厂家地址',width:200},
                                    {data:'bms_postcode',title:'BMS设备生产厂家邮编',width:200},
                                    {data:'bms_url',title:'BMS技术支持网址',width:200},
                                    {data:'bms_tel',title:'BMS技术支持固话',width:200},
                                    {data:'bms_phone',title:'BMS技术支持手机',width:200},
                                    {data:'bms_service_phone',title:'BMS服务商电话',width:200},
                                    {data:'bms_service_name',title:'BMS服务商名称',width:200},
                                    {data:'bms_service_url',title:'BMS服务商地址',width:300},
                                    {data:'bms_version',title:'软件版本号',width:150},
                                    {data:'bms_update_mark',title:'软件升级记录',width:170},
                                    {data:'bms_mark',title:'备注',width:300},
                                    {
                                        data:"id",
                                        render: function (data) {
                                            return _.template('<div style="width:160px">'+$("#editBtn").html()+$("#delBtn").html()+'</div>')({
                                                id:data
                                            });
                                        }
                                    }
                                ]
                            },dataTableDefaultOption)));
                            _this.checkAllRows();
                        })
                        return this;
                    }
                }
            },
            //用户单位信息信息表
            "stationInfo_institutions":{
                extObj:{
                    events:{
                        "click .list-edit-btn":"onEdit",
                        "click .list-del-btn":"onDel",
                        "mouseover .dataTable tr":"inRow",
                        "mouseout .dataTable tr":"inRow"
                    },
                    onEdit:function(e){
                        ui.showCompanyEditDialog($(e.currentTarget).attr('pid'));
                    },
                    onDel:function(e){
                        if(confirm("是否确定删除此UPS数据")){
                            API.deleteCompany({
                                id:$(e.currentTarget).attr('pid')
                            });
                        }
                    },
                    fetchData:function(){
                        API.getCompanyInfosData();
                    },
                    render:function(){
                        var _this = this;
                        _this.destoryPlugin();
                        $('#auto').width('100%');
                        require(["fixedColumn"],function() {
                            _this.listPlugin.push($('#auto table').DataTable( $.extend(true,{
                                "data": _this.data,
                                "scrollX":ui.getListHeight(),
                                "scrollY":ui.getListHeight(),
                                "fixedColumns": {rightColumns: 1},
                                "columns": [
                                    { "data": "id",title:"序号",width:50 },
                                    {data:'company_name',title:'用户单位总部名称',width:150},
                                    {data:'company_address',title:'用户单位总部地址',width:300},
                                    {data:'supervisor_phone',title:'主管领导电话',width:150},
                                    {data:'supervisor_name',title:'主管领导姓名',width:150},
                                    {data:'longitude',title:'经度',width:70},
                                    {data:'latitude',title:'纬度',width:70},
                                    {data:'station_num',title:'所辖站点个数',width:100},
                                    {data:'area_level',title:'隶属分几级',width:100},
                                    {data:'network_type',title:'联网方式',width:100},
                                    {data:'bandwidth',title:'网络带宽',width:100},
                                    {data:'ipaddr',title:'IP地址',width:100},
                                    {data:'computer_brand',title:'上位机品牌',width:100},
                                    {data:'computer_os',title:'上位机操作系统',width:150},
                                    {data:'computer_conf',title:'主机配置',width:100},
                                    {data:'browser_name',title:'浏览器名称',width:150},
                                    {data:'server_capacity',title:'服务器容量',width:150},
                                    {data:'server_type',title:'服务器型号',width:150},
                                    {data:'cloud_address',title:'云空间地址',width:150},
                                    {data:'backup_period',title:'数据备份周期',width:150},
                                    {data:'backup_type',title:'数据备份方式',width:150},
                                    {data:'supervisor_depname',title:'监控中心主管部门名称',width:200},
                                    {data:'monitor_name1',title:'监控中心负责人姓名1',width:200},
                                    {data:'monitor_phone1',title:'监控中心负责人电话1',width:200},
                                    {data:'monitor_name2',title:'监控中心负责人姓名2',width:200},
                                    {data:'monitor_phone2',title:'监控中心负责人电话2',width:200},
                                    {data:'monitor_name3',title:'监控中心负责人姓名3',width:200},
                                    {data:'monitor_phone3',title:'监控中心负责人电话3',width:200},
                                    {data:'monitor_tel1',title:'监控中心固定电话1',width:200},
                                    {data:'monitor_tel2',title:'监控中心固定电话2',width:200},
                                    {
                                        data:"id",
                                        render: function (data) {
                                            return _.template('<div style="width:160px">'+$("#editBtn").html()+$("#delBtn").html()+'</div>')({
                                                id:data
                                            });
                                        }
                                    }
                                ]
                            },dataTableDefaultOption)));
                            _this.checkAllRows();
                        })
                        return this;
                    }
                }
            },
            "manager_role":{
                extObj:{
                    fetchData:function(_param){
                        API.getRolesData(_param);
                    },
                    render:function(){
                        this.destoryPlugin();
                        //this.clearTables();
                        $('#lock').hide();
                        $('#auto').width('100%');
                        /*this.listPlugin.push($('#auto table').DataTable( $.extend(true,{
                            "data": this.data,
                            "scrollX":true,
                            "columns": [
                                { "data": "name" },
                                { "data": "id" },
                                { "data": "time" },
                                { "data": "a" },
                                { "data": "v" },
                                { "data": "temperature" },
                                { "data": "temperature_max" },
                                { "data": "temperature_min" },
                                { "data": "humidity" },
                                { "data": "humidity_max" },
                                { "data": "group_num" },
                                { "data": "battery_num" },
                                { "data": "battery_state" },
                                { "data": "reserve_time" }
                            ]
                        },dataTableDefaultOption)));*/
                        return this;
                    }
                }
            },
            //人员列表
            "manager_personal":{
                extObj:{
                    events:{
                        "click .list-edit-btn":"onEdit",
                        "click .list-del-btn":"onDel",
                        "mouseover .dataTable tr":"inRow",
                        "mouseout .dataTable tr":"inRow"
                    },
                    onEdit:function(e){
                        ui.showPersonalEditDialog($(e.currentTarget).attr('pid'));
                    },
                    onDel:function(e){
                        if(confirm("是否确定删除此人员")){
                            API.deletePersonal({
                                id:$(e.currentTarget).attr('pid')
                            });
                        }
                    },
                    fetchData:function(_param){
                        API.getPersonalsData(_param);
                    },
                    render:function(){
                        var _this = this;
                        this.destoryPlugin();
                        //this.clearTables();
                        $('#lock').hide();
                        require(["fixedColumn"],function() {
                            _this.listPlugin.push($('#auto table').DataTable($.extend(true, {}, dataTableDefaultOption, {
                                "data": _this.data,
                                "scrollX": ui.getListHeight(),
                                "scrollY": ui.getListHeight(),
                                "fixedColumns": {rightColumns: 1},
                                "columns": [
                                    {"data": "site_name", title: "序号"},
                                    {"data": "site_name", title: "站名称"},
                                    {"data": "Operater", title: "操作员"},
                                    {"data": "Operater_cellphone", title: "操作员手机"},
                                    {"data": "Alarm_SMS_receive_cellphone", title: "报警短信接收"},
                                    {"data": "Alarm_SMS_receive_email", title: "报警邮箱接收"},
                                    {
                                        data:"id",
                                        render: function (data) {
                                            return _.template('<div style="width:160px">'+$("#editBtn").html()+$("#delBtn").html()+'</div>')({
                                                id:data
                                            });
                                        }
                                    }
                                ]
                            })));
                            _this.checkAllRows();
                        })
                        return this;
                    }
                }
            },
            "message":{
                extObj:{
                    events:{
                        "click .list-edit-btn":"onEdit",
                        "click .list-del-btn":"onDel",
                        "mouseover .dataTable tr":"inRow",
                        "mouseout .dataTable tr":"inRow"
                    },
                    onEdit:function(e){
                        ui.showMessageEditDialog($(e.currentTarget).attr('pid'));
                    },
                    onDel:function(e){
                        if(confirm("是否确定删除此人员")){
                            API.deleteMessage({
                                id:$(e.currentTarget).attr('pid')
                            });
                        }
                    },
                    fetchData:function(_param){
                        API.getMessagesData(_param);
                    },
                    render:function(){
                        var _this = this;
                        this.destoryPlugin();
                        //this.clearTables();
                        $('#lock').hide();
                        require(["fixedColumn"],function() {
                            _this.listPlugin.push($('#auto table').DataTable($.extend(true, {}, dataTableDefaultOption, {
                                "data": _this.data,
                                "scrollX": ui.getListHeight(),
                                "scrollY": ui.getListHeight(),
                                "fixedColumns": {rightColumns: 1},
                                "columns": [
                                    {"data": "username", title: "序号"},
                                    {"data": "username", title: "接收人名称"},
                                    {"data": "phone", title: "手机号码"},
                                    {"data": "phone_status", title: "是否接收邮件",render:function(data){return createHasOrNotHtml(data)}},
                                    {"data": "email", title: "邮箱"},
                                    {"data": "email_status", title: "是否接收邮件",render:function(data){return createHasOrNotHtml(data)}},
                                    {
                                        data:"id",
                                        render: function (data) {
                                            return _.template('<div style="width:160px">'+$("#editBtn").html()+$("#delBtn").html()+'</div>')({
                                                id:data
                                            });
                                        }
                                    }
                                ]
                            })));
                        })
                        return this;
                    }
                }
            },
            //站参数
            "optionSetting_stationOption":{
                extObj:{
                    events:{
                        "click .list-edit-btn":"onEdit",
                        "mouseover .dataTable tr":"inRow",
                        "mouseout .dataTable tr":"inRow"
                    },
                    onEdit:function(e){
                        ui.showStationOptionEditDialog($(e.currentTarget).attr('pid'));
                    },
                    fetchData:function(_param){
                        API.getSationOptionsData(_param);
                    },
                    render:function(){
                        var _this = this;
                        this.destoryPlugin();
                        //this.clearTables();
                        $('#lock').hide();
                        require(["fixedColumn"],function() {
                            _this.listPlugin.push($('#auto table').DataTable($.extend(true, {}, dataTableDefaultOption, {
                                "data": _this.data.concat(_this.data),
                                "scrollX": ui.getListHeight(),
                                "scrollY": ui.getListHeight(),
                                "fixedColumns": {rightColumns: 1},
                                "columns": [
                                    {"data": "station_sn_key", title: "序号",width:50},
                                    {"data": "site_name", title: "站名称",width:100},
                                    {"data": "station_sn_key", title: "站序列号",width:100},
                                    {"data": "MAC_address", title: "物理地址",width:100},
                                    {"data": "sid", title: "站号",width:50},
                                    {"data": "N_Groups_Incide", title: "本站组数",width:100},
                                    {"data": "Time_interval_Rin", title: "内阻测量间隔(s)",width:200},
                                    {"data": "Time_interval_U", title: "电流测量间隔(s)",width:200},
                                    {"data": "U_abnormal_limit", title: "站电压异常限(v)",width:200},
                                    {"data": "T_abnormal_limit", title: "站温度异常限（℃）",width:200},
                                    {"data": "Rin_abnormal_limit", title: "站内阻异常限（mΩ）",width:200},
                                    {"data": "T_upper_limit", title: "站温度上限（℃）",width:200},
                                    {"data": "T_lower_limit", title: "站温度下限（℃）",width:200},
                                    {"data": "Humi_upper_limit", title: "站湿度上限（%）",width:200},
                                    {"data": "Humi_lower_limit", title: "站湿度下限（%）",width:200},
                                    {"data": "Group_I_criterion", title: "站电流状态判据（A）",width:200},
                                    {"data": "bytegeStatus_U_upper", title: "充电态高压限（V）",width:200},
                                    {"data": "bytegeStatus_U_lower", title: "充电态低压限（V）",width:200},
                                    {"data": "FloatingbytegeStatus_U_upper", title: "浮充态高压限（V）",width:200},
                                    {"data": "FloatingbytegeStatus_U_lower", title: "浮充态低压限（V）",width:200},
                                    {"data": "DisbytegeStatus_U_upper", title: "放电态高压限（V）",width:200},
                                    {"data": "DisbytegeStatus_U_lower", title: "放电态低压限（V）",width:200},
                                    {"data": "HaveCurrentSensor", title: "有无电流传感器",width:200,render:function(data){return createHasOrNotHtml(data)}},
                                    {"data": "StationCurrentSensorSpan", title: "电流传感器量程（A）",width:200},
                                    {"data": "StationCurrentSensorZeroADCode", title: "电流传感器零位AD码",width:200},
                                    {"data": "OSC", title: "OSC",width:50},
                                    {"data": "DisbytegeCurrentLimit", title: "放电电流限（A）",width:200},
                                    {"data": "bytegeCurrentLimit", title: "充电电流限（A）",width:200},
                                    {"data": "TemperatureHighLimit", title: "温度上限（℃）",width:200},
                                    {"data": "TemperatureLowLimit", title: "温度下限（℃）",width:200},
                                    {"data": "HumiH", title: "湿度上限（%）",width:200},
                                    {"data": "HumiL", title: "湿度下限（%）",width:200},
                                    {"data": "TemperatureAdjust", title: "温度传感温度偏移修正（℃）",width:250},
                                    {"data": "HumiAdjust", title: "湿度传感温度偏移修正（%）",width:250},
                                    {
                                        data:"sid",
                                        render: function (data) {
                                            return _.template($("#editBtn").html())({
                                                id:data
                                            });
                                        }
                                    }
                                ]
                            })));
                        })
                        return this;
                    }
                }
            },
            "optionSetting_groupOption":{
                extObj:{
                    events:{
                        "click .list-edit-btn":"onEdit",
                        "mouseover .dataTable tr":"inRow",
                        "mouseout .dataTable tr":"inRow"
                    },
                    onEdit:function(e){
                        ui.showGroupOptionEditDialog($(e.currentTarget).attr('pid'));
                    },
                    fetchData:function(_param){
                        API.getGroupOptionData(_param);
                    },
                    render:function(){
                        var _this = this;
                        this.destoryPlugin();
                        //this.clearTables();
                        $('#lock').hide();
                        require(["fixedColumn"],function() {
                            _this.listPlugin.push($('#auto table').DataTable($.extend(true, {}, dataTableDefaultOption, {
                                "data": _this.data.concat(_this.data),
                                "scrollX": ui.getListHeight(),
                                "scrollY": ui.getListHeight(),
                                "fixedColumns": {rightColumns: 1},
                                "columns": [
                                    {"data": "group_sn_key", title: "序号",width:50},
                                    {"data": "site_name", title: "站名称",width:100},
                                    {"data": "group_sn_key", title: "组序列号",width:100},
                                    {"data": "sid", title: "站号",width:50},
                                    {"data": "gid", title: "组号",width:50},
                                    {"data": "K_Battery_Incide", title: "本组电池数",width:100},
                                    {"data": "HaveCurrentSensor", title: "有无电流传感器",width:200,render:function(data){return createHasOrNotHtml(data)}},
                                    {"data": "StationCurrentSensorSpan", title: "电流传感器量程（A）",width:200},
                                    {"data": "StationCurrentSensorZeroADCode", title: "电流传感器零位AD码",width:200},
                                    {"data": "OSC", title: "OSC",width:50},
                                    {"data": "DisbytegeCurrentLimit", title: "放电电流限（A）",width:200},
                                    {"data": "bytegeCurrentLimit", title: "充电电流限（A）",width:200},
                                    {"data": "TemperatureHighLimit", title: "温度上限（℃）",width:100},
                                    {"data": "TemperatureLowLimit", title: "温度下限（℃）",width:100},
                                    {"data": "HumiH", title: "湿度上限（%）",width:100},
                                    {"data": "HumiL", title: "湿度下限（%）",width:100},
                                    {"data": "TemperatureAdjust", title: "温度传感温度偏移修正（℃）",width:200},
                                    {"data": "HumiAdjust", title: "湿度传感温度偏移修正（%）",width:200},
                                    {
                                        data:"gid",
                                        render: function (data) {
                                            return _.template($("#editBtn").html())({
                                                id:data
                                            });
                                        }
                                    }
                                ]
                            })));
                        })
                        return this;
                    }
                }
            },
            //电池参数
            "optionSetting_batteryOption":{
                extObj:{
                    events:{
                        "click .list-edit-btn":"onEdit",
                        "mouseover .dataTable tr":"inRow",
                        "mouseout .dataTable tr":"inRow"
                    },
                    onEdit:function(e){
                        ui.showBatteryOptionEditDialog($(e.currentTarget).attr('pid'));
                    },
                    fetchData:function(_param){
                        API.getBatteryOptionsData(_param);
                    },
                    render:function(){
                        var _this = this;
                        this.destoryPlugin();
                        $('#auto').width('100%');
                        require(["fixedColumn"],function() {
                            _this.listPlugin.push($('#auto table').DataTable($.extend(true, {}, dataTableDefaultOption, {
                                "data": _this.data,
                                "scrollX": ui.getListHeight(),
                                "scrollY": ui.getListHeight(),
                                "fixedColumns": {rightColumns: 1},
                                "columns": [
                                    {"data": "sid", title: "序号",width:50},
                                    {"data": "site_name", title: "站名称",width:100},
                                    {"data": "battery_sn_key", title: "电池序列号",width:100},
                                    {"data": "sid", title: "站号",width:50},
                                    {"data": "gid", title: "组号",width:50},
                                    {"data": "bid", title: "电池号",width:50},
                                    {"data": "CurrentAdjust_KV", title: "电压测量修正系数KV",width:200},
                                    {"data": "TemperatureAdjust_KT", title: "温度测量修正系数KI",width:200},
                                    {"data": "T0_ADC", title: "T0校准点ADC码",width:200},
                                    {"data": "T0_Temperature", title: "T0校准点温度值（℃）",width:200},
                                    {"data": "T1_ADC", title: "T1校准点ADC码",width:200},
                                    {"data": "T1_Temperature", title: "T1校准点温度值（℃）",width:200},
                                    {"data": "Rin_Span", title: "内阻测量量程",width:200},
                                    {"data": "OSC", title: "OSC_Voltage",width:200},
                                    {"data": "BatteryU_H", title: "电池电压高压限（V）",width:200},
                                    {"data": "BaterryU_L", title: "电池电压低压限（V）",width:200},
                                    {"data": "Electrode_T_H_Limit", title: "电极温度高温限（℃）",width:200},
                                    {"data": "Electrode_T_L_Limit", title: "电极温度低温限（℃）",width:200},
                                    {"data": "Rin_High_Limit", title: "电池内阻高限（mΩ）",width:200},
                                    {"data": "Rin_Adjust_KR", title: "内阻测量修正系数KR",width:200},
                                    {"data": "PreAmp_KA", title: "前置放大器修正系数KA",width:200},
                                    {"data": "Rin_ExciteI_KI", title: "内阻测量激励电流修正系数KI",width:200},
                                    {
                                        data:"bid",
                                        render: function (data) {
                                            return _.template($("#editBtn").html())({
                                                id:data
                                            });
                                        }
                                    }
                                ]
                            })));
                        })
                        return this;
                    }
                }
            },
            //门限
            "limitationSetting":{
                extObj:{
                    events:{
                        "click .list-edit-btn":"onEdit",
                        "mouseover .dataTable tr":"inRow",
                        "mouseout .dataTable tr":"inRow"
                    },
                    onEdit:function(e){
                        ui.showLimitationEditDialog($(e.currentTarget).attr('pid'));
                    },
                    fetchData:function(_param){
                        API.getStationsInfo(_param);
                    },
                    render:function() {
                        var _this = this;
                        _this.destoryPlugin();
                        require(["fixedColumn"], function () {
                            _this.listPlugin.push($('#auto table').DataTable($.extend(true, {
                                "data": [_this.data[0]],
                                "language": {
                                    "emptyTable": "站点数据为空"
                                },
                                "scrollX": ui.getListHeight(),
                                "scrollY": ui.getListHeight(),
                                "fixedColumns": {rightColumns: 1},
                                "columns": [
                                    {"data": "sid", title: "序号",width:50},
                                    {"data": "sid", title: "站号", width: 100},
                                    {"data": "site_name", title: "站点简称", width: 200},
                                    {"data": "serial_number", title: "物理地址", width: 250},
                                    {
                                        data: "id",
                                        render: function (data) {
                                            return _.template('<div style="width:80px; margin:0px auto">'+$("#editBtn").html()+'</div>')({
                                                id: data
                                            });
                                        }
                                    }
                                ]
                            }, dataTableDefaultOption)));

                        })
                    }
                }
            },
            //外控设备
            "equipmentSetting":{
                extObj:{
                    events:{
                        "click .list-edit-btn":"onEdit",
                        "click .list-del-btn":"onDel",
                        "mouseover .dataTable tr":"inRow",
                        "mouseout .dataTable tr":"inRow"
                    },
                    onEdit:function(e){
                        ui.showStationdeviceEditDialog($(e.currentTarget).attr('pid'));
                    },
                    onDel:function(e){
                        if(confirm("是否确定删除此外控设备")){
                            API.deleteStationdevice({
                                id:$(e.currentTarget).attr('pid')
                            });
                        }
                    },
                    fetchData:function(_param){
                        API.getStationdeviceInfos(_param);
                    },
                    render:function() {
                        var _this = this;
                        _this.destoryPlugin();
                        require(["fixedColumn"], function () {
                            _this.listPlugin.push($('#auto table').DataTable($.extend(true, {
                                "data": _this.data,
                                "language": {
                                    "emptyTable": "站点数据为空"
                                },
                                "scrollX": ui.getListHeight(),
                                "scrollY": ui.getListHeight(),
                                "fixedColumns": {rightColumns: 1},
                                "columns": [
                                    {"data": "sid", title: "序号",width:50},
                                    {"data": "sid", title: "站号", width: 100},
                                    {"data": "Device_name", title: "名称", width: 100},
                                    {"data": "Device_fun", title: "功能", width: 100},
                                    {"data": "Device_Factory_Name", title: "生产厂家", width: 100},
                                    {"data": "Device_Factory_Address", title: "厂家地址", width: 200},
                                    {"data": "Device_Factory_PostCode", title: "厂家邮编", width: 100},
                                    {"data": "Device_Factory_website", title: "技术支持网址", width: 150},
                                    {"data": "Device_Factory_Technology_cable_phone", title: "技术支持固话", width: 100},
                                    {"data": "Device_Factory_Technology_cellphone", title: "技术支持手机", width: 100},
                                    {
                                        data: "id",
                                        render: function (data) {
                                            return _.template('<div style="width:160px; margin:0px auto">'+$("#editBtn").html()+$("#clearBtn").html()+'</div>')({
                                                id: data
                                            });
                                        }
                                    }
                                ]
                            }, dataTableDefaultOption)));

                        })
                    }
                }
            },
            //报表：报警历史
            "reportCaution":{
                extObj:{
                    fetchData:function(_param){
                        API.getGerneralalarmlog(_param);
                    },
                    render:function() {
                        var _this = this;
                        _this.destoryPlugin();
                        _this.listPlugin.push($('#auto table').DataTable($.extend(true, {
                            "data": _this.data,
                            "language": {
                                "emptyTable": "报表数据为空"
                            },
                            "scrollX": ui.getListHeight(),
                            "scrollY": ui.getListHeight(),
                            "columns": [
                                {"data": "id", title: "序号",width:50},
                                {"data": "id", title: "站号", width: 100},
                                {"data": "begin", title: "开始时间", width: 100},
                                {"data": "end", title: "结束时间", width: 100},
                                {
                                    "data": "type",
                                    title: "类型",
                                    render:function(data,itemData){
                                        switch(data){
                                            case '0':
                                                return '<label style="color:#f86464">红</label>+<label style="color:#fbf17d">黄</label>'
                                            case '1':
                                                return '<label style="color:#f86464">红</label>'
                                            case '2':
                                                return '<label style="color:#fbf17d">黄</label>'
                                        }
                                    }
                                }
                            ]
                        }, dataTableDefaultOption)));
                    }
                }
            },
            //报表：电池使用年限
            "batteryLife":{
                extObj:{
                    fetchData:function(_param){
                        API.getByearlog(_param);
                    },
                    render:function() {
                        var _this = this;
                        _this.destoryPlugin();
                        _this.listPlugin.push($('#auto table').DataTable($.extend(true, {
                            "data": _this.data,
                            "language": {
                                "emptyTable": "报表数据为空"
                            },
                            "scrollX": ui.getListHeight(),
                            "scrollY": ui.getListHeight(),
                            "columns": [
                                {"data": "brand", title: "序号",width:50},
                                {"data": "brand", title: "评判", width: 100},
                                {"data": "battery_date", title: "生产日期", width: 100},
                                {"data": "battery_install_date", title: "电池安装日期", width: 100},
                                {"data": "U", title: "电池的电压", width: 100},
                                {"data": "battery_oum", title: "出厂标称内阻", width: 200},
                                {"data": "R", title: "电池的内阻", width: 100},
                                {"data": "battery_scrap_date", title: "强制报废日期"}
                            ]
                        }, dataTableDefaultOption)));
                    }
                }
            } ,
            // 报表：UI日志：设置
            "reportUilog_options":{
                extObj:{
                    fetchData:function(_param){
                        API.getUserlog({type:'2'});
                    },
                    render:function() {
                        var _this = this;
                        _this.destoryPlugin();
                        _this.listPlugin.push($('#auto table').DataTable($.extend(true, {
                            "data": _this.data,
                            "language": {
                                "emptyTable": "UI日志数据为空"
                            },
                            "scrollX": ui.getListHeight(),
                            "scrollY": ui.getListHeight(),
                            "columns": [
                                {"data": "type", title: "序号",width:50},
                                {"data": "username", title: "用户", width: 100},
                                {"data": "content", title: "操作内容"},
                                {"data": "Modify_time", title: "操作时间", width: 150}
                            ]
                        }, dataTableDefaultOption)));
                    }
                }
            }
        };
    //报表：UI日志：用户登录登出
    listConfig.reportUilog_user = $.extend(true,{},listConfig.reportUilog_options,{
        extObj:{
            fetchData:function(_param){
                API.getUserlog({type:'1'});
            }
        }
    })
    //报表：UI日志：其他
    listConfig.reportUilog_other = $.extend(true,{},listConfig.reportUilog_options,{
        extObj:{
            fetchData:function(_param){
                API.getUserlog({type:'3'});
            }
        }
    })


    //查询：UI日志：设置
    listConfig.uilog_options = $.extend(true,{},listConfig.reportUilog_options)
    //查询：UI日志：用户登录登出
    listConfig.uilog_user = $.extend(true,{},listConfig.reportUilog_user)
    //查询：UI日志：其他
    listConfig.uilog_other = $.extend(true,{},listConfig.reportUilog_other)
    //查询：站
    listConfig.qureyStation = $.extend(true,{},listConfig.station)
    //查询：组
    listConfig.qureyGroup = $.extend(true,{},listConfig.group)
    //查询：电池
    listConfig.qureyBattery = $.extend(true,{},listConfig.battery,{
        extObj:{
            fetchData:function(_param){
                var _param = {};
                var navData = nav.getBatterys();

                $.extend(_param,{id:navData.ids.join(",")});
                API.getBatterysRealTimeData(_param);
            },
        }
    })
    //查询：门限
    listConfig.limitation = $.extend(true,{},listConfig.limitationSetting,{extObj:{
        render:function() {
            var _this = this;
            _this.destoryPlugin();
            require(["fixedColumn"], function () {
                _this.listPlugin.push($('#auto table').DataTable($.extend(true, {
                    "data": [_this.data[0]],
                    "language": {
                        "emptyTable": "站点数据为空"
                    },
                    "scrollX": ui.getListHeight(),
                    "scrollY": ui.getListHeight(),
                    "fixedColumns": {rightColumns: 1},
                    "columns": [
                        {"data": "sid", title: "序号",width:50},
                        {"data": "sid", title: "站号", width: 100},
                        {"data": "site_name", title: "站点简称", width: 200},
                        {"data": "serial_number", title: "物理地址"}
                    ]
                }, dataTableDefaultOption)));

            })
        }
    }})
    //查询：基本信息：站
    listConfig.baseinfo_queryStationSituation = $.extend(true,{},listConfig.stationInfo_stationSituation,{extObj:{
        render:function(){
            var _this = this;
            _this.destoryPlugin();
            require(["fixedColumn"],function() {
                _this.listPlugin.push($('#auto table').DataTable( $.extend(true,{
                    "data": _this.data,
                    "language": {
                        "emptyTable": "站点数据为空"
                    },
                    "scrollX": ui.getListHeight(),
                    "scrollY": ui.getListHeight(),
                    "columns": [
                        { "data": "sid",title:"序号",width:50},
                        { "data": "sid",title:"站号",width:100  },
                        { "data": "serial_number",title:"物理地址",width:50  },
                        { "data": "site_name",title:"站点简称",width:100  },
                        { "data": "StationFullChineseName",title:"站点全称",width:100  },
                        { "data": "site_location",title:"站点地址",width:100  },
                        { "data": "site_property",title:"站点性质",width:150  },
                        { "data": "aid",title:"隶属区域",width:150  },
                        { "data": "emergency_person",title:"紧急联系人姓名",width:150  },
                        { "data": "emergency_phone",title:"紧急联系人手机",width:250  },
                        { "data": "groups",title:"电池组数",width:100  },
                        { "data": "batteries",title:"每组电池数",width:250  },
                        { "data": "postal_code",title:"邮政编码",width:250  },
                        { "data": "site_latitude",title:"站点纬度",width:250  },
                        { "data": "site_longitude",title:"站点经度",width:250  },
                        { "data": "ipaddress",title:"IP地址",width:250  },
                        { "data": "ipaddress_method",title:"控制器IP地址或方式",width:150  },
                        { "data": "site_control_type",title:"站点控制器型号",width:200  },
                        { "data": "bms_install_date",title:"BMS系统安装日期",width:150  },
                        { "data": "group_collect_type",title:"组电流采集器型号",width:150  },
                        { "data": "group_collect_num",title:"组电流采集器数量",width:150  },
                        { "data": "inductor_brand",title:"互感器品牌",width:150  },
                        { "data": "group_collect_install_type",title:"组电流采集器安装模式",width:150  },
                        { "data": "battery_collect_type",title:"电池数据采集器型号",width:150  },
                        { "data": "battery_collect_num",title:"电池数据采集器数量",width:150  },
                        { "data": "humiture_type",title:"环境温湿度方式",width:150  }
                    ]
                },dataTableDefaultOption)));

            })
            return this;
        }
    }})
    //查询：基本信息：电池
    listConfig.baseinfo_queryBatterys = $.extend(true,{},listConfig.stationInfo_batterys,{extObj:{
        render:function(){
            var _this = this;
            _this.destoryPlugin();
            require(["fixedColumn"],function() {
                _this.listPlugin.push($('#auto table').DataTable( $.extend(true,{
                    "data": _this.data,
                    "language": {
                        "emptyTable": "电池数据为空"
                    },
                    "scrollX": ui.getListHeight(),
                    "scrollY": ui.getListHeight(),
                    "fixedColumns": {rightColumns: 1},
                    "columns": [
                        { "data": "sid",title:"序号",width:50 },
                        { "data": "sid",title:"站号",width:100  },
                        { "data": "site_name",title:"站点简称",width:100  },
                        { "data": "battery_factory",title:"生产厂家",width:150  },
                        { "data": "battery_num",title:"电池型号",width:150  },
                        { "data": "battery_num",title:"生产日期",width:150  },
                        { "data": "battery_voltage",title:"标称电压（V）",width:150  },
                        { "data": "battery_oum",title:"标称内阻（毫欧）",width:150  },
                        { "data": "battery_max_current",title:"最大充电电流（A）",width:150  },
                        { "data": "battery_float_up",title:"浮充电压上限（V）",width:150  },
                        { "data": "battery_float_dow",title:"电池浮充电压下限（V）",width:150  },
                        { "data": "battery_discharge_down",title:"放电电压下限（V）",width:150  },
                        { "data": "battery_scrap_date",title:"强制报废日期",width:150  },
                        { "data": "battery_life",title:"设计寿命（年）",width:150  },
                        { "data": "battery_column_type",title:"电池级柱类型",width:150  },
                        { "data": "battery_temperature",title:"温湿度要求（度）",width:150  },
                        { "data": "battery_humidity",title:"湿度要求（%）",width:150  },
                        { "data": "battery_type",title:"电池种类",width:150  },
                        { "data": "battery_factory_phone",title:"电池厂家联系电话",width:150  },
                        {
                            data:"id",
                            render: function (data) {
                                return _.template('<div style="width:160px">'+$("#editBtn").html()+$("#delBtn").html()+'</div>')({
                                    id:data
                                });
                            }
                        }
                    ]
                },dataTableDefaultOption)));

            })
            return this;
        }
    }})
    //查询：基本信息：用户单位信息信息表
    listConfig.baseinfo_queryInstitutions = $.extend(true,{},listConfig.stationInfo_institutions,{extObj:{
        render:function(){
            var _this = this;
            _this.destoryPlugin();
            $('#auto').width('100%');
            require(["fixedColumn"],function() {
                _this.listPlugin.push($('#auto table').DataTable( $.extend(true,{
                    "data": _this.data,
                    "scrollX":ui.getListHeight(),
                    "scrollY":ui.getListHeight(),
                    "fixedColumns": {rightColumns: 1},
                    "columns": [
                        { "data": "id",title:"序号",width:50 },
                        {data:'company_name',title:'用户单位总部名称',width:150},
                        {data:'company_address',title:'用户单位总部地址',width:300},
                        {data:'supervisor_phone',title:'主管领导电话',width:150},
                        {data:'supervisor_name',title:'主管领导姓名',width:150},
                        {data:'longitude',title:'经度',width:70},
                        {data:'latitude',title:'纬度',width:70},
                        {data:'station_num',title:'所辖站点个数',width:100},
                        {data:'area_level',title:'隶属分几级',width:100},
                        {data:'network_type',title:'联网方式',width:100},
                        {data:'bandwidth',title:'网络带宽',width:100},
                        {data:'ipaddr',title:'IP地址',width:100},
                        {data:'computer_brand',title:'上位机品牌',width:100},
                        {data:'computer_os',title:'上位机操作系统',width:150},
                        {data:'computer_conf',title:'主机配置',width:100},
                        {data:'browser_name',title:'浏览器名称',width:150},
                        {data:'server_capacity',title:'服务器容量',width:150},
                        {data:'server_type',title:'服务器型号',width:150},
                        {data:'cloud_address',title:'云空间地址',width:150},
                        {data:'backup_period',title:'数据备份周期',width:150},
                        {data:'backup_type',title:'数据备份方式',width:150},
                        {data:'supervisor_depname',title:'监控中心主管部门名称',width:200},
                        {data:'monitor_name1',title:'监控中心负责人姓名1',width:200},
                        {data:'monitor_phone1',title:'监控中心负责人电话1',width:200},
                        {data:'monitor_name2',title:'监控中心负责人姓名2',width:200},
                        {data:'monitor_phone2',title:'监控中心负责人电话2',width:200},
                        {data:'monitor_name3',title:'监控中心负责人姓名3',width:200},
                        {data:'monitor_phone3',title:'监控中心负责人电话3',width:200},
                        {data:'monitor_tel1',title:'监控中心固定电话1',width:200},
                        {data:'monitor_tel2',title:'监控中心固定电话2',width:200}
                    ]
                },dataTableDefaultOption)));
            })
            return this;
        }
    }})
    //查询：基本信息：BMS信息表
    listConfig.baseinfo_queryMonitorSeller = $.extend(true,{},listConfig.stationInfo_monitorSeller,{extObj:{
        render:function(){
            var _this = this;
            _this.destoryPlugin();
            $('#auto').width('100%');
            require(["fixedColumn"],function() {
                _this.listPlugin.push($('#auto table').DataTable( $.extend(true,{
                    "data": _this.data,
                    "scrollX":ui.getListHeight(),
                    "scrollY":ui.getListHeight(),
                    "fixedColumns": {rightColumns: 1},
                    "columns": [
                        { "data": "id",title:"序号",width:50 },
                        {data:'bms_company',title:'BMS设备生产厂家名称',width:200},
                        {data:'bms_device_addr',title:'BMS设备生产厂家地址',width:200},
                        {data:'bms_postcode',title:'BMS设备生产厂家邮编',width:200},
                        {data:'bms_url',title:'BMS技术支持网址',width:200},
                        {data:'bms_tel',title:'BMS技术支持固话',width:200},
                        {data:'bms_phone',title:'BMS技术支持手机',width:200},
                        {data:'bms_service_phone',title:'BMS服务商电话',width:200},
                        {data:'bms_service_name',title:'BMS服务商名称',width:200},
                        {data:'bms_service_url',title:'BMS服务商地址',width:300},
                        {data:'bms_version',title:'软件版本号',width:150},
                        {data:'bms_update_mark',title:'软件升级记录',width:170},
                        {data:'bms_mark',title:'备注',width:300}
                    ]
                },dataTableDefaultOption)));
            })
            return this;
        }
    }})
    //查询：参数：组
    listConfig.option_groupOption = $.extend(true,{},listConfig.optionSetting_groupOption,{extObj:{
        render:function(){
            var _this = this;
            this.destoryPlugin();
            //this.clearTables();
            $('#lock').hide();
            require(["fixedColumn"],function() {
                _this.listPlugin.push($('#auto table').DataTable($.extend(true, {}, dataTableDefaultOption, {
                    "data": _this.data.concat(_this.data),
                    "scrollX": ui.getListHeight(),
                    "scrollY": ui.getListHeight(),
                    "fixedColumns": {rightColumns: 1},
                    "columns": [
                        {"data": "group_sn_key", title: "序号",width:50},
                        {"data": "site_name", title: "站名称",width:100},
                        {"data": "group_sn_key", title: "组序列号",width:100},
                        {"data": "sid", title: "站号",width:50},
                        {"data": "gid", title: "组号",width:50},
                        {"data": "K_Battery_Incide", title: "本组电池数",width:100},
                        {"data": "HaveCurrentSensor", title: "有无电流传感器",width:200,render:function(data){return createHasOrNotHtml(data)}},
                        {"data": "StationCurrentSensorSpan", title: "电流传感器量程（A）",width:200},
                        {"data": "StationCurrentSensorZeroADCode", title: "电流传感器零位AD码",width:200},
                        {"data": "OSC", title: "OSC",width:50},
                        {"data": "DisbytegeCurrentLimit", title: "放电电流限（A）",width:200},
                        {"data": "bytegeCurrentLimit", title: "充电电流限（A）",width:200},
                        {"data": "TemperatureHighLimit", title: "温度上限（℃）",width:100},
                        {"data": "TemperatureLowLimit", title: "温度下限（℃）",width:100},
                        {"data": "HumiH", title: "湿度上限（%）",width:100},
                        {"data": "HumiL", title: "湿度下限（%）",width:100},
                        {"data": "TemperatureAdjust", title: "温度传感温度偏移修正（℃）",width:200},
                        {"data": "HumiAdjust", title: "湿度传感温度偏移修正（%）",width:200}
                    ]
                })));
            })
            return this;
        }
    }})
    //查询：参数：站
    listConfig.option_stationOption = $.extend(true,{},listConfig.optionSetting_stationOption,{extObj:{
        render:function(){
            var _this = this;
            this.destoryPlugin();
            //this.clearTables();
            $('#lock').hide();
            require(["fixedColumn"],function() {
                _this.listPlugin.push($('#auto table').DataTable($.extend(true, {}, dataTableDefaultOption, {
                    "data": _this.data.concat(_this.data),
                    "scrollX": ui.getListHeight(),
                    "scrollY": ui.getListHeight(),
                    "fixedColumns": {rightColumns: 1},
                    "columns": [
                        {"data": "station_sn_key", title: "序号",width:50},
                        {"data": "site_name", title: "站名称",width:100},
                        {"data": "station_sn_key", title: "站序列号",width:100},
                        {"data": "MAC_address", title: "物理地址",width:100},
                        {"data": "sid", title: "站号",width:50},
                        {"data": "N_Groups_Incide", title: "本站组数",width:100},
                        {"data": "Time_interval_Rin", title: "内阻测量间隔(s)",width:200},
                        {"data": "Time_interval_U", title: "电流测量间隔(s)",width:200},
                        {"data": "U_abnormal_limit", title: "站电压异常限(v)",width:200},
                        {"data": "T_abnormal_limit", title: "站温度异常限（℃）",width:200},
                        {"data": "Rin_abnormal_limit", title: "站内阻异常限（mΩ）",width:200},
                        {"data": "T_upper_limit", title: "站温度上限（℃）",width:200},
                        {"data": "T_lower_limit", title: "站温度下限（℃）",width:200},
                        {"data": "Humi_upper_limit", title: "站湿度上限（%）",width:200},
                        {"data": "Humi_lower_limit", title: "站湿度下限（%）",width:200},
                        {"data": "Group_I_criterion", title: "站电流状态判据（A）",width:200},
                        {"data": "bytegeStatus_U_upper", title: "充电态高压限（V）",width:200},
                        {"data": "bytegeStatus_U_lower", title: "充电态低压限（V）",width:200},
                        {"data": "FloatingbytegeStatus_U_upper", title: "浮充态高压限（V）",width:200},
                        {"data": "FloatingbytegeStatus_U_lower", title: "浮充态低压限（V）",width:200},
                        {"data": "DisbytegeStatus_U_upper", title: "放电态高压限（V）",width:200},
                        {"data": "DisbytegeStatus_U_lower", title: "放电态低压限（V）",width:200},
                        {"data": "HaveCurrentSensor", title: "有无电流传感器",width:200,render:function(data){return createHasOrNotHtml(data)}},
                        {"data": "StationCurrentSensorSpan", title: "电流传感器量程（A）",width:200},
                        {"data": "StationCurrentSensorZeroADCode", title: "电流传感器零位AD码",width:200},
                        {"data": "OSC", title: "OSC",width:50},
                        {"data": "DisbytegeCurrentLimit", title: "放电电流限（A）",width:200},
                        {"data": "bytegeCurrentLimit", title: "充电电流限（A）",width:200},
                        {"data": "TemperatureHighLimit", title: "温度上限（℃）",width:200},
                        {"data": "TemperatureLowLimit", title: "温度下限（℃）",width:200},
                        {"data": "HumiH", title: "湿度上限（%）",width:200},
                        {"data": "HumiL", title: "湿度下限（%）",width:200},
                        {"data": "TemperatureAdjust", title: "温度传感温度偏移修正（℃）",width:250},
                        {"data": "HumiAdjust", title: "湿度传感温度偏移修正（%）",width:250}
                    ]
                })));
            })
            return this;
        }
    }})
    //查询：参数：电池
    listConfig.option_batteryOption = $.extend(true,{},listConfig.optionSetting_batteryOption,{extObj:{
        render:function(){
            var _this = this;
            this.destoryPlugin();
            $('#auto').width('100%');
            require(["fixedColumn"],function() {
                _this.listPlugin.push($('#auto table').DataTable($.extend(true, {}, dataTableDefaultOption, {
                    "data": _this.data,
                    "scrollX": ui.getListHeight(),
                    "scrollY": ui.getListHeight(),
                    "fixedColumns": {rightColumns: 1},
                    "columns": [
                        {"data": "sid", title: "序号",width:50},
                        {"data": "site_name", title: "站名称",width:100},
                        {"data": "battery_sn_key", title: "电池序列号",width:100},
                        {"data": "sid", title: "站号",width:50},
                        {"data": "gid", title: "组号",width:50},
                        {"data": "bid", title: "电池号",width:50},
                        {"data": "CurrentAdjust_KV", title: "电压测量修正系数KV",width:200},
                        {"data": "TemperatureAdjust_KT", title: "温度测量修正系数KI",width:200},
                        {"data": "T0_ADC", title: "T0校准点ADC码",width:200},
                        {"data": "T0_Temperature", title: "T0校准点温度值（℃）",width:200},
                        {"data": "T1_ADC", title: "T1校准点ADC码",width:200},
                        {"data": "T1_Temperature", title: "T1校准点温度值（℃）",width:200},
                        {"data": "Rin_Span", title: "内阻测量量程",width:200},
                        {"data": "OSC", title: "OSC_Voltage",width:200},
                        {"data": "BatteryU_H", title: "电池电压高压限（V）",width:200},
                        {"data": "BaterryU_L", title: "电池电压低压限（V）",width:200},
                        {"data": "Electrode_T_H_Limit", title: "电极温度高温限（℃）",width:200},
                        {"data": "Electrode_T_L_Limit", title: "电极温度低温限（℃）",width:200},
                        {"data": "Rin_High_Limit", title: "电池内阻高限（mΩ）",width:200},
                        {"data": "Rin_Adjust_KR", title: "内阻测量修正系数KR",width:200},
                        {"data": "PreAmp_KA", title: "前置放大器修正系数KA",width:200},
                        {"data": "Rin_ExciteI_KI", title: "内阻测量激励电流修正系数KI",width:200}
                    ]
                })));
            })
            return this;
        }
    }})
    //查询：外控设备
    listConfig.equipment = $.extend(true,{},listConfig.equipmentSetting,{extObj:{
        render:function() {
            var _this = this;
            _this.destoryPlugin();
            require(["fixedColumn"], function () {
                _this.listPlugin.push($('#auto table').DataTable($.extend(true, {
                    "data": _this.data,
                    "language": {
                        "emptyTable": "站点数据为空"
                    },
                    "scrollX": ui.getListHeight(),
                    "scrollY": ui.getListHeight(),
                    "columns": [
                        {"data": "sid", title: "序号",width:50},
                        {"data": "sid", title: "站号", width: 100},
                        {"data": "Device_name", title: "名称", width: 100},
                        {"data": "Device_fun", title: "功能", width: 100},
                        {"data": "Device_Factory_Name", title: "生产厂家", width: 100},
                        {"data": "Device_Factory_Address", title: "厂家地址", width: 200},
                        {"data": "Device_Factory_PostCode", title: "厂家邮编", width: 100},
                        {"data": "Device_Factory_website", title: "技术支持网址", width: 150},
                        {"data": "Device_Factory_Technology_cable_phone", title: "技术支持固话", width: 100},
                        {"data": "Device_Factory_Technology_cellphone", title: "技术支持手机"}
                    ]
                }, dataTableDefaultOption)));

            })
        }
    }})

    function initPage(listType,sub,ids){
        if(listView){
            listView.destroy();
            $("#list").html('');
        }
        var _listType = listType?listType+(sub?"_"+sub:""):'defaultConfig';
        var _listTpl = 'defaultListTpl';
        var _extObj= listConfig[_listType]?listConfig[_listType].extObj:{};
        _extObj = $.extend({},listConfig.defaultConfig.extObj,_extObj);
        $("#list").html($("#"+_listTpl).html());
        listView = new (Backbone.View.extend($.extend({},{el:$("#list"),ids:ids},_extObj)))();
        overFlag = false;
        listView.fetchData();
    }

    $(window).resize(function(){
        if(listView && listView.listPlugin){
            listView.fetchData();
        }
    })

    function createCautionStatusHtml(code){
        return code == '1'?'<label style="color:#ff0000">是</label>':'<label style="color:#888">否</label>';
    }
    function createHasOrNotHtml(code){
        return code == '1'?'<label>是</label>':'<label>否</label>';
    }
    return {
        init:function(sys,listType,sub,ids){
            _sys = sys;
            _listType = listType;
            _sub = sub;

            initPage(listType,sub,ids);
            stationInfoDialog.init();

            var html = '',shtml='';
            $.each([
                {"data": "battery_sn_key", title: "电池序列号",width:100},
                {"data": "sid", title: "站号",width:50},
                {"data": "gid", title: "组号",width:50},
                {"data": "bid", title: "电池号",width:50},
                {"data": "CurrentAdjust_KV", title: "电压测量修正系数KV",width:200},
                {"data": "TemperatureAdjust_KT", title: "温度测量修正系数KI",width:200},
                {"data": "T0_ADC", title: "T0校准点ADC码",width:200},
                {"data": "T0_Temperature", title: "T0校准点温度值（℃）",width:200},
                {"data": "T1_ADC", title: "T1校准点ADC码",width:200},
                {"data": "T1_Temperature", title: "T1校准点温度值（℃）",width:200},
                {"data": "Rin_Span", title: "内阻测量量程",width:200},
                {"data": "OSC", title: "OSC_Voltage",width:200},
                {"data": "BatteryU_H", title: "电池电压高压限（V）",width:200},
                {"data": "BaterryU_L", title: "电池电压低压限（V）",width:200},
                {"data": "Electrode_T_H_Limit", title: "电极温度高温限（℃）",width:200},
                {"data": "Electrode_T_L_Limit", title: "电极温度低温限（℃）",width:200},
                {"data": "Rin_High_Limit", title: "电池内阻高限（mΩ）",width:200},
                {"data": "Rin_Adjust_KR", title: "内阻测量修正系数KR",width:200},
                {"data": "PreAmp_KA", title: "前置放大器修正系数KA",width:200},
                {"data": "Rin_ExciteI_KI", title: "内阻测量激励电流修正系数KI",width:200}
            ],function(i,d){
                shtml+= _.template('<label class="item-title" for="name"><%= title %>: </label> <input class="input-w-1" type="text" key="<%=data%>"/>')(d)
                if(i%2){
                    html+= '<div class="rowElem">'+shtml+'</div>';
                    shtml='';
                }
            })
            if(shtml){
                html+= '<div class="rowElem">'+shtml+'</div>';
            }
            //console.log(html);

            return this;
        },
        isOver:function(value){
            if(typeof value == 'undefined'){
                return !!overFlag;
            }else{
                overFlag = !!value;
            }
        },
        destory:function(){
            if(listView){
                console.log('destroy list');
                listView.destroy();
                $("#list").html('');
            }
            return this;
        }
    };
})