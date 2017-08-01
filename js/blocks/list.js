define(['require','api','blocks/nav','stationsinfoDialog','context','ui','common','table','fixedHeader'],function(require,API,nav,stationInfoDialog,context,ui,common){
    var listView = null,
        pageView = null,
        _listType,_sub,_sys,
        overFlag = false,
        dataTableDefaultOption = {
            "paging": false,
            "searching": true,
            "order": [ ],
            "autoWidth": true,
            "scrollCollapse":true,
            "language": {
                "lengthMenu": "显示 _MENU_ 条",
                "paginate":{
                    "first":"首页",
                    "last":"末页",
                    "next":"下一页",
                    "previous":"上一页",
                    'info': '第 _PAGE_ 页 / 总 _PAGES_ 页'
                },
                "zeroRecords": "暂无数据，请查看左侧数形结构是否已勾选或检查网络连接"

            },
            "zeroRecords": "暂无数据，请查看左侧数形结构是否已勾选或检查网络连接",
                "dom":"irtlp",
            "scroller": {
                "rowHeight": 'auto'
            },
            "fixedHeadere": {
                header:true
            },
            "fnDrawCallback": function ( oSettings ) {
                /* Need to redo the counters if filtered or sorted */
                // if ( oSettings.bSorted || oSettings.bFiltered ) {
                //     for ( var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ ) {
                //         $('td:eq(0)', oSettings.aoData[ oSettings.aiDisplay[i] ].nTr ).html('<label class="index">'+ (i+1)+'</label>' );
                //     }
                // }
            },
            "info":false
        },
        listConfig = {
            defaultConfig:{
                extObj : {
                    data:null,
                    el:'#list',
                    "listPlugin":[],
                    events:{
                        "click .dataTable tr":"selectRow",
                        "click .show-info":"openStationInfoDialog",
                        "mouseover .dataTable tr":"inRow",
                        "mouseout .dataTable tr":"inRow"
                    },
                    initialize:function(data){
                        var _this = this;
                        //_this.destroy();
                        _this.listPlugin=[];
                        _this.captureEvt();
                        _this.curPage = 1;
                        _this.totalpage = 1;
                    },
                    checkAllRows: function(){
                        return;
                        var _this = this;
                        setTimeout(function(){
                            var allRows = $('.dataTable tr', _this.el);

                            for(var i = 0 ; i < allRows.length; i++){
                                _this.changeRowClass($(allRows[i]),'selected')
                            }
                            _this.triggerSelectEvent();
                        },0)


                    },
                    captureEvt:function(){

                        var _this = this;
                        $(".mnext").click(function(){
                            console.log('to next page');
                            if(_this.curPage >= _this.totalpage){
                                return;
                            }
                            _this.curPage++;
                            _this.fetchData();
                        });
                        $(".mprev").click(function(){
                            if(_this.curPage == 1){
                                return;
                            }
                            _this.curPage--;
                            console.log(_this.curPage)
                            _this.fetchData();
                        });
                        _this.listenTo(Backbone.Events,"listdata:update stationdata:get",function(data){
                            console.log('listdata update', data);
                            if(data.types){
                                _this.types = data.types;
                            }
                            if(!$(_this.el).length || !$(_this.el).is(":visible")){
                                return;
                            }
                            var page = data.page,
                                totals = data.totals || 0,
                                pageSize = data.pageSize || 10;
                            _this.data = data.list||data||_this.data;
                            $("#totaldatas").text(totals);
                            $("#currentpage").text(_this.curPage);
                            $("#totalpage").text(Math.ceil(totals/(pageSize||10)));
                            $('#ctotals').text(data.list.length);
                            _this.totalpage = Math.ceil(totals/(pageSize||10));
                            if(_this.curPage == 1){
                            //    $(".mprev").hide();
                            }else{
                                $(".mprev").show();
                            }
                            if(_this.data.length < 10){
                            //    $(".mnext").hide();
                            }else{
                                $(".mnext").show();
                            }
                            var ctype = /qurey/.test(window.location.href) ? 1 : 0;
                            _this.render(ctype);
                            _this.renderChart();
                            console.log('render over');
                            setTimeout(function(){
                                var roleid = JSON.parse(localStorage.getItem('userinfo')).role;
                                var canedit = JSON.parse(localStorage.getItem('userinfo')).canedit;
                                if(roleid == 2 && canedit != 1){
                                    console.log('start to hide',$(".list-edit-btn"));
                                    $(".list-edit-btn").hide();
                                    $(".list-del-btn").hide();
                                    $("#listBtns li.undis").hide();
                                }
                            },100);



                            overFlag = true;
                        });

                        _this.listenTo(Backbone.Events,"search:done",function(){
                            _this.fetchData();
                            Backbone.Events.trigger("curstation:change",{});
                        });
                        _this.listenTo(Backbone.Events,"export:done",function(){
                            console.log(_this.downloadUrl, window.location.hash);
                            if(!_this.downloadUrl){
                                return;
                            }
                            console.log(_this.downloadUrl, window.location.hash);
                            var navData = nav.getSites();
                            var ids;

                            if(this.ids && this.ids.sid){
                                ids = this.ids.sid;
                            }else{
                                ids = navData.ids.join(",");
                            }
                            var hash = window.location.hash;
                            if(window.location.hash.indexOf("/qurey/") > -1){
                                var startTime = $("#beginTime").val();
                                var endTime = $("#endTime").val();
                                if(startTime && endTime){
                                    startTime = +new Date(startTime);
                                    endTime = +new Date(endTime);
                                }

                                if(hash.indexOf("Group") > -1){
                                    navData = nav.getGroups();
                                    ids = navData.ids.join(",");
                                }else if(hash.indexOf("Battery") > -1){

                                        var batteryId = nav.getBatterys();
                                        ids = batteryId?batteryId.ids.join(','):'';
                                }

                                if(this.downloadUrl.indexOf("?") > -1){
                                    var durl = _this.downloadUrl + "&isdownload=1&start="+startTime+"&end="+endTime+"&id="+ids;
                                }else{
                                    var durl = _this.downloadUrl + "?isdownload=1&start="+startTime+"&end="+endTime+"&id="+ids;
                                }


                                window.location = durl;
                                //query页面
                            }else if(window.location.hash.indexOf("/report/") > -1){
                                //报表导出
                                var startTime = $("#beginTime").val();
                                var endTime = $("#endTime").val();
                                if(startTime && endTime){
                                    startTime = +new Date(startTime);
                                    endTime = +new Date(endTime);
                                }
                                var type = $("#cationCategory").val();
                                if(this.downloadUrl.indexOf("?") > -1){
                                    var durl = _this.downloadUrl + "&isdownload=1&start="+startTime+"&end="+endTime+"&id="+ids;
                                }else{
                                    var durl = _this.downloadUrl + "?isdownload=1&start="+startTime+"&end="+endTime+"&id="+ids;
                                }


                                window.location = durl;
                            }
                            // _this.refresh();
                        });
                        _this.listenTo(Backbone.Events,"listdata:refresh",function(){
                            Backbone.Events.trigger("curstation:change",{});
                            _this.refresh();
                        });
                        _this.listenTo(Backbone.Events,"listdata:update:fail stationdata:get:fail",function(data){
                            if(data.response.code == "-1"){
                                overFlag = true;
                                _this.data = [];
                                var ctype = /qurey/.test(window.location.href) ? 1 : 0;
                                _this.render(ctype);
                            }
                        });
                        _this.listenTo(Backbone.Events,"listitem:delete",function(data){
                            alert("删除成功");
                            _this.refresh();
                        });
                        
                        _this.listenTo(Backbone.Events,"curstation:change",function(data){
                            _this.ids = null;
                            if(!$(_this.el).length || !$(_this.el).is(":visible")){
                                return;
                            }
                            var navData = _this.getNavData();
                            if(typeof navData == 'undefined' || !navData || !navData.ids.length){
                                _this.data = [];
                                var ctype = /qurey/.test(window.location.href) ? 1 : 0;
                                _this.render(ctype);
                                // _this.render();
                            }else{
                                _this.fetchData();
                            }
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
                    },
                    changeRowClass:function($tr,className){
                        var _this = this,
                            trIndex = $tr.index(),
                            $tbodys = $("tbody",_this.el),
                            data = _this.getRowData($tr);

                        // console.log(trIndex, data);
                        if(!data || !data.data){
                            return this;
                        }

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
                        this.remove();
                        this.destoryPlugin();
                        this.clearTables();
                        $('#dataItem').off('click');
                    },
                    triggerSelectEvent:function(){
                        //整理数据发送选择行
                        var _this = this,
                            selectedData = _this.listPlugin[0]?_this.listPlugin[0].rows('.selected').data():[],
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
                        //this.destoryPlugin();
                        this.curPage = 1;
                        this.fetchData();
                    },
                    updateList:function(){
                        var _this = this;
                        var _listdata = [].concat(_this.data),
                            _lisiLen = _listdata.length;
                        _this.listPlugin[0].rows().remove();
                        /*_this.listPlugin[0].rows().every(function(rowIdx, tableLoop, rowLoop){
                            if(_.isEqual(this.data(),_listdata[rowIdx])){
                                return;
                            }
                            if(_listdata.length){
                                this.data(_listdata.splice(0,1)[0]);
                            }else{//删除
                                this.remove().draw();
                            }
                        });*/
                        if(_listdata.length){
                            _this.listPlugin[0].rows.add(_listdata).draw();
                        }

                        if(_this.listPlugin[0].fixedColumns){
                            _this.listPlugin[0].fixedColumns().update();
                            _this.listPlugin[0].fixedColumns().relayout();
                        }
                        _this.listPlugin[0].draw();

                        _this.checkAllRows();

                    },
                    fetchData:function(_param){
                        var _param = _param || {};
                        _param.page=this.curPage;
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
                    renderChart:function(){},
                    onAddStation:function(){}
                }
            },
            station:{
                extObj:{
                    getNavData:function(){
                        return nav.getSites();
                    },
                    selectedIds:null,
                    events:{
                        "click .show_station_detail":"show_station_detail"
                    },
                    extEvent:function(){
                        var _this = this;
                        _this.listenTo(Backbone.Events,"stationColsChange",function(data){
                            console.log('station col change');
                            _this.destoryPlugin();
                            _this.render();
                        });
                    },
                    show_station_detail:function(e){
                        var id = [$(e.currentTarget).attr('id')];
                        console.log('station pop clicked');

                        console.log('id',id)
                        if(id < 0){
                            alert('请选择站点');
                            return;
                        }
                        for(var i = 0 ; i < id.length ; i++){
                            id[i] = id[i];
                        }

                        stationInfoDialog.show(id.join(","));
                    },
                    fetchData:function(){
                        var _param = {page:this.curPage};
                        var navData = nav.getSites();
                        var ids;

                        if(this.ids && this.ids.sid){
                            ids = this.ids.sid;
                        }else{
                            ids = navData.ids.join(",");
                        }
                        // _param.page = this.curPage;
                        $.extend(_param,{id:ids});
                        this.selectedIds = ids;
                        API.getStationRealTimeData(_param);
                    },
                    renderChart:function(){
                        console.log('chart data', this.data);
                        var wraps = $("#down .btn-wrap .btn-bg");
                        var ele = null;
                        for(var i = 0 ; i < wraps.length ; i++){
                            if($(wraps[i]).css('display') == "block"){
                                console.log('yes block');
                                ele = $(wraps[i]).find('.active').attr('field');
                                break;
                            }
                        }
                        console.log(ele);
                    },
                    render:function(ctype){
                        var _this = this,colums = _this.getCols(ctype == 1 ? 'qurey_station':"station");
                        console.log(ctype);
                        //_this.destoryPlugin();
                        if(_this.listPlugin && _this.listPlugin[0]){
                            _this.updateList();
                        }else{
                            require(["fixedColumn"],function(){
                                if(colums.width+580>$("#list").width()){
                                    dataTableDefaultOption.fixedColumns = {leftColumns:2};
                                }else{

                                    try{
                                        delete colums.data[colums.data.length-1].width;
                                    }catch(e){}
                                }
                                console.log(ui.getListWidth());
                                _this.listPlugin.push($('#auto table').DataTable( $.extend(true,{
                                    "data": _this.data,
                                    "scrollX":ui.getListWidth(),
                                    "scrollY":ui.getListHeight(),
                                    "columns": [
                                        { "data": "site_name", "title":"站名",width:150,"sClass":"site_name_left",render:function(data,type,itemData){
                                            return "<div class='show_station_detail' style='color:blue;cursor:pointer;' id='"+itemData.sn_key+"'>"+data+"</div>"
                                        }},
                                        { "data": "sid","title":"站号",width:50 },

                                    ].concat(colums.data)
                                },dataTableDefaultOption)));
                                _this.checkAllRows();

                                //_this.resetScrollBar();
                            })
                        }
                        //this.clearTables();
                        ui.resizeAutoListWidth();

                        return this;
                    }
                }
            },
            battery:{
                extObj:{

                    prevIds:[],
                    curStation:'',

                    //el:$('#dataItem'),
                    onNext:function(){
                        this.nextStation();
                        this._fetch();
                    },
                    onPrev:function(){
                        this.prevStation();
                        this._fetch();
                    },
                    nextStation:function(){
                        if(!this.stations || !this.stations.ids.length){return}
                        $("#page").show();
                        this.curStation && this.prevIds.push(this.curStation);
                        this.curStation = this.stations.ids.shift();
                    },
                    prevStation:function(){
                        if(!this.prevIds.length){return}
                        $("#page").show();
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

                        this.stations.ids = this.stations.ids.sort(function(a,b){
                            return a-b;
                        })
                        console.log(stations, curStation);

                        var batteryId = nav.getBatterys(curStation);
                        if(curStation){
                            $("#page .cur").html('当前站点：'+stations.map[curStation].title);
                            this.updatePageView();
                        }
                        return batteryId?batteryId.ids.join(','):'';
                    },
                    updateStations:function(){

                        this.stations = nav.getTrueSites();
                        console.log('stations',this.stations)
                        this.prevIds = [];
                        this.curStation = '';
                        return this;
                    },
                    refresh:function(){
                        this._fetch();
                    },
                    getNavData:function(){
                        return nav.getBatterys(this.curStation);
                    },
                    fetchData:function(){
                        this.updateStations();
                        if(!this.curStation){
                            this.curStation = this.stations[0];
                        }
                        // if(this.curStation && this.curStation == this.stations[0]){
                        //     return;
                        // }
                        this.nextStation();
                        this._fetch();
                    },
                    _fetch:function(){
                        var _this = this,_param = {};
                        $.extend(_param,{
                            id:this.getBatterys()
                        });
                        if(!_param.id){
                            _this.data=[];
                            if(_this.listPlugin && _this.listPlugin[0]){
                                _this.updateList();
                            }else{
                                _this.render();
                            }
                            return;
                        }
                        console.log('_param'+ $.toJSON(_param));
                        API.getBatterysRealTimeData(_param);
                    },
                    extEvent:function(){
                        var _this = this;
                        _this.listenTo(Backbone.Events,"batteryColsChange",function(data){
                            _this.destoryPlugin();
                            _this.render();
                        });
                        $('#dataItem').on('click','#page .prev',function(){_this.onPrev()});
                        $('#dataItem').on('click','#page .next',function(){_this.onNext()});
                    },
                    render:function(ctype){
                        var _this = this,colums = _this.getCols(ctype == 1 ?'qurey_battery':'battery');
                        if(this.data.length == 0){
                            $("#page").hide();
                        }
                        //this.destoryPlugin();
                        if(_this.listPlugin && _this.listPlugin[0]){
                            _this.updateList();
                        }else{
                            console.log('colums.width', colums.width);
                            console.log('list.width',$('#list').width());
                            require(["fixedColumn"],function(){
                                if(colums.width + 450>$("#list").width()){
                                    console.log('width >')
                                    dataTableDefaultOption.fixedColumns = {leftColumns:4};
                                }else{
                                    try{
                                        delete colums.data[colums.data.length-1].width;
                                    }catch(e){}
                                }
                                _this.listPlugin.push($('#auto table').DataTable( $.extend(true,{
                                    "data": _this.data,
                                    "scrollX":ui.getListWidth(),
                                    "scrollY":ui.getListHeight(),
                                    "leftColumns":4,
                                    "columns": [
                                        { "data": "site_name",title:"站名",width:120,"sClass":"site_name_left"},
                                        { "data": "sid",title:"站号",width:50 },
                                        { "data": "gid",title:"组号",width:50 },
                                        { "data": "bid",title:"电池号",width:50  }
                                    ].concat(colums.data)
                                },dataTableDefaultOption)));
                                _this.checkAllRows();
                            })
                        }
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
                        var _param = {page:this.curPage};
                        var navData = nav.getGroups();

                        $.extend(_param,{id:navData.ids.join(",")});
                        API.getGroupsData(_param);
                    },
                    extEvent:function(){
                        var _this = this;
                        _this.listenTo(Backbone.Events,"groupColsChange",function(data){
                            _this.destoryPlugin();
                            _this.render();
                        });
                    },
                    render:function(ctype){
                        var _this = this,colums = _this.getCols(ctype == 1?'qurey_group':'group');
                        //_this.destoryPlugin();
                        if(_this.listPlugin && _this.listPlugin[0]){
                            _this.updateList();
                        }else{
                            require(["fixedColumn"],function(){
                                if(colums.width+530>$("#list").width()){
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
                                        { "data": "site_name",title:"站名",width:120 ,"sClass":"site_name_left"},
                                        { "data": "sid",title:"站号",width:50 },
                                        { "data": "gid",title:"组号",width:50 }
                                    ].concat(colums.data)
                            })));
                                _this.checkAllRows();
                            })

                        }

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
                        var _param = {page:this.curPage};
                        var navData = nav.getSites();

                        $.extend(_param,{id:navData.ids.join(","), type:0,});
                        API.getCautionsData(_param);
                    },
                    events:{
                        "click .resolveBtn":"resove",
                        "click .show_station_detail": 'show_station_detail'
                    },
                    show_station_detail:function(e){
                        var id = [$(e.currentTarget).attr('id')];
                        console.log('station pop clicked');

                        console.log('id',id)
                        if(id < 0){
                            alert('请选择站点');
                            return;
                        }
                        for(var i = 0 ; i < id.length ; i++){
                            id[i] = id[i];
                        }

                        stationInfoDialog.show(id.join(","));
                    },
                    resove:function(e){
                        ui.showUnsolveDialog({id:$(e.currentTarget).attr('pid'),suggestion:$(e.currentTarget).attr('suggestion')});
                    },
                    updateAtype: function(){
                        console.log('atypes',this.types);
                        for(var i = 0 ; i < this.types.length; i++){
                            var obj = this.types[i];
                            if(obj.atype == "R"){
                                $("#ared").html(obj.count+"条");
                            }else if(obj.atype == "O"){
                                $("#aoo").html(obj.count+"条");
                            }else if(obj.atype == "Y"){
                                $("#ayellow").html(obj.count+"条");
                            }
                        }
                    },
                    render:function(){
                        //this.destoryPlugin();
                        var _this = this;
                        //ui.resizeAutoListWidth();
                        if(_this.listPlugin && _this.listPlugin[0]){
                            _this.updateList();
                            _this.updateAtype()
                        }else{
                            _this.updateAtype()
                            this.listPlugin.push($('#auto table').DataTable( $.extend(true,{},dataTableDefaultOption,{
                                "data": this.data,
                                "paging":false,
                                "scrollX":ui.getListHeight(),
                                //"scrollY":ui.getListHeight(),
                                "columns": [

                                    { "data": "site_name", "title":"站名",width:150,"sClass":"site_name_left",render:function(data,type,itemData){
                                            return "<div class='show_station_detail' style='color:blue;cursor:pointer;' id='"+(itemData.sn_key.substring(0,10)+"0000")+"'>"+data+"</div>"
                                    }},
                                    { "data": "sn_key",title:"站号",width:100, render:function(data, type,itemData){
                                        return itemData.sid;
                                    }},
                                    { "data": "sn_key",title:"组号",width:100, render:function(data, type,itemData){
                                        if(itemData.type == "battery" || itemData.type == "group"){
                                            return itemData.sn_key.substring(10,12);
                                        }else{
                                            return "";
                                        }

                                    }  },//组序列号
                                    { "data": "sn_key",title:"电池号",width:100, render:function(data, type,itemData){
                                        if(itemData.type == "battery")
                                            return itemData.sn_key.substring(12,14);
                                        else{
                                            return "";
                                        }
                                    }},
                                    { "data": "time",title:"时间" ,width:200},
                                    { "data": "desc",title:"警情内容",width:200,render:function(data, type, itemData){
                                        var desc = itemData.desc;
                                        var color="";
                                        if(desc.indexOf("橙") > -1){
                                            color="#e7691d"
                                        }else if(desc.indexOf("黄") > -1){
                                            color="#fabd05"
                                        }else{
                                            color="#f20f0f"
                                        }
                                        return "<div style='color:"+color+"'>"+data+"</div>";
                                    }},
                                    { "data": "current",title:"数值",width:80,render:function(data, type, itemData){
                                        var desc = itemData.desc;
                                        var color="";
                                        if(desc.indexOf("橙") > -1){
                                            color="#e7691d"
                                        }else if(desc.indexOf("黄") > -1){
                                            color="#fabd05"
                                        }else{
                                            color="#f20f0f"
                                        }
                                        return "<div style='font-weight:bold;color:"+color+"'>"+data+"</div>";
                                    }},
                                    { "data": "climit",title:"参考值",width:80},
                                    { "data": "markup",title:"操作记录",width:400,render:function(data){
                                        var a = data == null ? "": data;

                                        return '<div style="text-align:left;">'+a+'</div>';
                                    }},
                                    {
                                        "data": "id",
                                        title:"操作",
                                        width:100,
                                        render: function (data,type,itemData) {
                                            return _.template('<a class="resolveBtn" pid="<%=id%>" suggestion="<%=suggest%>">处理</a>')({
                                                id:data,
                                                suggest:itemData.suggest
                                            });
                                        }
                                    },
                                    //{ "data": "alarm_process_and_memo",title:"处理过程、时间、管理员" }
                                ]
                            })));
                        }

                        // _this.checkAllRows();
                        return this;
                    }
                }
            },
            newstations:{
                extObj:{
                    getNavData:function(){
                        return nav.getSites();
                    },
                    fetchData:function(_param){
                        var _param = {page:this.curPage};

                        API.getNewStationData(_param);
                    },
                    events:{
                        "click .resolveBtn":"resove"
                    },
                    showStationOptionEditDialog:function(data){
                        require(["dialogstationEdit"],function(dialog){
                            dialog && dialog.show(null,data);
                        })
                    },
                    resove:function(e){
                        var target=$(e.currentTarget);
                        this.showStationOptionEditDialog({
                            sid:target.attr('sid'),
                            sn_key:target.attr('sn_key'),
                            Groups:target.attr('Groups'),
                            GroBats:target.attr("GroBats"),
                            CurSensor:target.attr("CurSensor")
                        });


                        // ui.showUnsolveDialog({id:$(e.currentTarget).attr('pid'),suggestion:$(e.currentTarget).attr('suggestion')});
                    },
                    updateAtype: function(){
                        console.log('atypes',this.types);
                        for(var i = 0 ; i < this.types.length; i++){
                            var obj = this.types[i];
                            if(obj.atype == "R"){
                                $("#ared").html(obj.count+"条");
                            }else if(obj.atype == "O"){
                                $("#aoo").html(obj.count+"条");
                            }else if(obj.atype == "Y"){
                                $("#ayellow").html(obj.count+"条");
                            }
                        }
                    },
                    render:function(){
                        //this.destoryPlugin();
                        var _this = this;
                        //ui.resizeAutoListWidth();
                        if(_this.listPlugin && _this.listPlugin[0]){
                            _this.updateList();

                        }else{

                            this.listPlugin.push($('#auto table').DataTable( $.extend(true,{},dataTableDefaultOption,{
                                "data": this.data,
                                "paging":false,
                                "scrollX":ui.getListHeight(),
                                //"scrollY":ui.getListHeight(),
                                "columns": [

                                    { "data": "sn_key",title:"物理地址"},
                                    { "data": "CurSensor",title:"电流传感安装状态"},
                                    { "data": "sid",title:"站号"},
                                    { "data": "Groups",title:"组数"},
                                    { "data": "GroBats",title:"电池数"},
                                    { "data": "record_time",title:"时间"},//组序列号

                                    {
                                        "data": "id",
                                        title:"操作",
                                        width:150,
                                        render: function (data,type,itemData) {
                                            return _.template('<a class="resolveBtn" sn_key="<%=sn_key%>" GroBats="<%=GroBats%>" sid="<%=sid%>" Groups="<%=Groups%>" CurSensor="<%=CurSensor%>" >添加</a>')(itemData);
                                        }
                                    },
                                    //{ "data": "alarm_process_and_memo",title:"处理过程、时间、管理员" }
                                ]
                            })));
                        }

                        // _this.checkAllRows();
                        return this;
                    }
                }
            },
            // 系统报警
            systemAlarm:{
                extObj:{
                    getNavData:function(){
                        return nav.getSites();
                    },
                    fetchData:function(_param){
                        var _param = {page:this.curPage};
                        API.getSystemAlarm(_param);
                    },

                    render:function(){
                        //this.destoryPlugin();
                        var _this = this;
                        //ui.resizeAutoListWidth();
                        if(_this.listPlugin && _this.listPlugin[0]){
                            _this.updateList();

                        }else{

                            this.listPlugin.push($('#auto table').DataTable( $.extend(true,{},dataTableDefaultOption,{
                                "data": this.data,
                                "paging":false,
                                "scrollX":ui.getListHeight(),
                                //"scrollY":ui.getListHeight(),
                                "columns": [
                                    { "data": "site_name",title:"站名"},
                                    { "data": "sid",title:"站号"},
                                    { "data": "station",title:"物理地址"},
                                    { "data": "record_time",title:"时间"},//组序列号
                                    { "data": "desc", title:'故障描述'},
                                    { "data": "tips", title:'建议处理方式'}
                                ]
                            })));
                        }

                        // _this.checkAllRows();
                        return this;
                    }
                }
            },


            "stationInfo_stationSituation":{
                extObj:{
                    events:{
                        "click .list-edit-btn":"onEdit",
                        "click .list-edit-text":"onEdit",
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
                            this.refresh();
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
                            common.loadTips.close();
                            alert("校验成功");

                            _this.refresh();
                        });
                        this.listenTo(Backbone.Events,"station:check:fail",function(data){
                            common.loadTips.close();
                            alert('校验失败');

                            // _this.refresh();
                        });
                    },
                    getNavData:function(){
                        return nav.getSites();
                    },
                    fetchData:function(_param){
                        _param = _param || {};
                        var navData = nav.getSites();
                        var ids;

                        if(this.ids && this.ids.sid){
                            ids = this.ids.sid;
                        }else{
                            ids = navData.ids.join(",");
                        }

                        $.extend(_param,{id:ids});
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
                                "fixedColumns": {leftColumns: 2},
                                "columns": [

                                    { "data": "sid",title:"站号",width:80  },
                                    {
                                        "data": "site_name",
                                        title:"站点简称",
                                        width:100 ,
                                        render: function (data,type,itemData) {
                                            var tpl='';
                                            tpl = '<div class="list-edit-text" pid="'+itemData.id+'">'+itemData.site_name+'</div>';

                                            return tpl;
                                        }
                                    },
                                    { "data": "serial_number",title:"物理地址",width:100},
                                    
                                    {
                                        "data": "site_name",
                                        title:"站点全称",
                                        width:100 ,
                                        render: function (data,type,itemData) {
                                            var tpl='';
                                            tpl = '<div class="list-edit-text" pid="'+itemData.id+'">'+itemData.StationFullChineseName+'</div>';

                                            return tpl;
                                        }
                                    },

                                    { "data": "site_location",title:"站点地址",width:100  },
                                    { "data": "site_property",title:"站点性质",width:150  },
                                    { "data": "areaname",title:"隶属区域",width:150  },
                                    { "data": "fix_phone",title:"固定电话"},
                                    { "data": "functionary",title:"负责人",width:120,render:function(data,_,allData){
                                        var html = [];
                                        if(allData.functionary_sms == 1){
                                            html.push('<span>')
                                            html.push('<img src="/images/sms.png">');
                                            html.push("</span>");
                                        }
                                        

                                        if(allData.functionary_mail){
                                            html.push('<span>')
                                            html.push('<img src="/images/email.png">');
                                            html.push("</span>");
                                        }

                                        return "<span>"+data+"</span>"+html.join("");
                                    }},
                                    { "data": "functionary_phone",title:"负责人电话",width:120},
                                    { "data": "device_owner",title:"设备负责人",width:120},
                                    { "data": "device_owner_phone",title:"设备负责人电话",width:120},

                                    { "data": "emergency_person",title:"紧急联系人姓名",width:120 },
                                    { "data": "emergency_phone",title:"紧急联系人手机",width:120  },
                                    { "data": "area_owner",title:"区域主管",width:120,render:function(data,_,allData){
                                        var html = [];
                                        if(allData.area_owner_sms == 1){
                                            html.push('<span>')
                                            html.push('<img src="/images/sms.png">');
                                            html.push("</span>");
                                        }
                                        

                                        if(allData.area_owner_mail){
                                            html.push('<span>')
                                            html.push('<img src="/images/email.png">');
                                            html.push("</span>");
                                        }

                                        return "<span>"+data+"</span>"+html.join("");
                                    }},
                                    { "data": "area_owner_phone",title:"区域主管电话",width:120},
                                    { "data": "parent_owner",title:"上级主管",width:120,render:function(data,_,allData){
                                        var html = [];
                                        if(allData.parent_owner_sms == 1){
                                            html.push('<span>')
                                            html.push('<img src="/images/sms.png">');
                                            html.push("</span>");
                                        }
                                        

                                        if(allData.parent_owner_mail){
                                            html.push('<span>')
                                            html.push('<img src="/images/email.png">');
                                            html.push("</span>");
                                        }

                                        return "<span>"+data+"</span>"+html.join("");
                                    }},
                                    { "data": "parent_owner_phone",title:"上级主管电话"},
                                    { "data": "groups",title:"电池组数",width:100  },
                                    { "data": "batteries",title:"每组电池数",width:80  },
                                    { "data": "postal_code",title:"邮政编码",width:80  },
                                    { "data": "site_latitude",title:"站点纬度",width:80  },
                                    { "data": "site_longitude",title:"站点经度",width:80  },
                                    { "data": "ipaddress",title:"IP地址",width:100  },
                                    { "data": "ipaddress_method",title:"控制器IP地址或方式",width:150  },
                                    { "data": "site_control_type",title:"站点控制器型号",width:100  },
                                    { "data": "bms_install_date",title:"BMS系统安装日期",width:150  },
                                    { "data": "group_collect_type",title:"组电流采集器型号",width:150  },
                                    { "data": "group_collect_num",title:"组电流采集器数量",width:150  },
                                    { "data": "inductor_brand",title:"互感器品牌",width:150  },
                                    { "data": "group_collect_install_type",title:"组电流采集器安装模式",width:150  },
                                    { "data": "battery_collect_type",title:"电池数据采集器型号",width:150  },
                                    { "data": "battery_collect_num",title:"电池数据采集器数量",width:150  },
                                    { "data": "humiture_type",title:"环境温湿度方式",width:150  },
                                    { "data": "has_light",title:"灯光报警",width:80},
                                    { "data": "has_speaker",title:"声音报警",width:80},
                                    { "data": "has_sms",title:"短信",width:80},
                                    { "data": "has_smart_control",title:"智能控制",width:80},
                                    { "data": "has_group_TH_control",title:"温湿度传感器",width:150},
                                    { "data": "has_group_HO_control",title:"氢氧气传感器",width:150},
                                    { "data": "device_mac",title:"网口MAC",width:150},
                                    { "data": "remark",title:"备注",width:150 },
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
                        "mouseover .dataTable tr":"inRow",
                        "mouseout .dataTable tr":"inRow"
                    },
                    onEdit:function(e){
                        ui.showBatteryEditDialog($(e.currentTarget).attr('pid'));
                    },

                    getNavData:function(){
                        return nav.getSites();
                    },
                    fetchData:function(_param){
                        _param = _param || {};
                        var navData = nav.getSites();
                        var ids;

                        if(this.ids && this.ids.sid){
                            ids = this.ids.sid;
                        }else{
                            ids = navData.ids.join(",");
                        }

                        $.extend(_param,{id:ids});
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
                                "fixedColumns": {leftColumns: 2},
                                "columns": [

                                    { "data": "sid",title:"站号",width:100  },
                                    { "data": "site_name",title:"站点简称",width:100  },
                                    { "data": "battery_factory",title:"生产厂家",width:150  },
                                    { "data": "battery_num",title:"电池型号",width:150  },
                                    { "data": "battery_date",title:"生产日期",width:150  },
                                    { "data": "battery_voltage",title:"标称电压（V）",width:150  },
                                    { "data": "battery_oum",title:"标称内阻（MΩ）",width:150  },
                                    { "data": "battery_dianrong",title:"电池标称容量（Ah）",width:150  },
                                    { "data": "battery_float_voltage",title:"浮充标准电压（V）",width:150  },
                                    { "data": "battery_max_current",title:"最大充电电流（A）",width:150  },
                                    { "data": "battery_max_discharge_current",title:"最大放电电流（A）",width:150  },
                                    { "data": "battery_float_up",title:"浮充电压上限（V）",width:150  },
                                    { "data": "battery_float_dow",title:"电池浮充电压下限（V）",width:150  },
                                    { "data": "battery_discharge_down",title:"放电电压下限（V）",width:150  },
                                    { "data": "battery_scrap_date",title:"强制报废日期",width:150  },
                                    { "data": "battery_life",title:"设计寿命（年）",width:150  },
                                    { "data": "battery_column_type",title:"电池级柱类型",width:150  },
                                    { "data": "battery_temperature",title:"温度要求（℃）",width:150  },
                                    { "data": "battery_humidity",title:"湿度要求（%）",width:150  },
                                    { "data": "battery_type",title:"电池种类",width:150  },
                                    { "data": "battery_factory_phone",title:"电池厂家联系电话",width:150  },
                                    { "data": "remark",title:"备注",width:150 },
                                    {
                                        data:"id",
                                        render: function (data) {
                                            return _.template('<div style="width:160px">'+$("#editBtn").html()+'</div>')({
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
                    getNavData:function(){
                        return nav.getSites();
                    },
                    fetchData:function(_param){
                        _param = _param || {};
                        var navData = nav.getSites();
                        var ids;

                        if(this.ids && this.ids.sid){
                            ids = this.ids.sid;
                        }else{
                            ids = navData.ids.join(",");
                        }

                        $.extend(_param,{id:ids});
                        API.getUpsInfosData(_param);
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
                                "fixedColumns": {leftColumns: 2},
                                "columns": [
                                    { "data": "sid",title:"站号",width:80 },
                                    { "data": "site_name",title:"站名",width:250 },
                                    
                                    { "data": "ups_factory",title:"生产厂家",width:250 },
                                    { "data": "ups_type",title:"型号",width:100 },
                                    { "data": "ups_create_date",title:"生产日期",width:150 },
                                    { "data": "ups_install_date",title:"安装日期",width:150 },
                                    { "data": "ups_power",title:"功率/容量（W/H）",width:150 },
                                    { "data": "ups_power_in",title:"输入功率（W/A）",width:150 },
                                    { "data": "ups_power_out",title:"输出功率（W/A）",width:150 },
                                    { "data": "ups_battery_vol",title:"外接电池电压（V）",width:150 },
                                    { "data": "ups_battery_current",title:"外接电池电流（A）",width:150 },
                                    { "data": "ac_protect",title:"AC过流保护（V/A）",width:150 },
                                    { "data": "dc_protect",title:"DC过流保护（V/A）",width:150 },
                                    { "data": "on_net",title:"联网检测",width:150 },
                                    { "data": "alarm_content",title:"报警内容",width:150 },
                                    { "data": "discharge_protect",title:"放电保护值（%）",width:150 },
                                    { "data": "redundancy_num",title:"冗余数量(台)",width:150 },
                                    { "data": "floting_charge",title:"浮充电压（V）",width:150 },
                                    { "data": "ups_vdc",title:"电压范围(V)",width:150 },
                                    { "data": "ups_reserve_hour",title:"额定候备时间（W/H）",width:250 },
                                    { "data": "ups_charge_mode",title:"充电方式",width:100 },
                                    { "data": "ups_max_charge",title:"最大充电电流（A）",width:150 },
                                    { "data": "ups_max_discharge",title:"最大放电电流（A）",width:150 },
                                    { "data": "ups_period_days",title:"规定维护周期（天）",width:150 },
                                    { "data": "ups_discharge_time",title:"维护放电时长（分钟）",width:150 },
                                    { "data": "ups_discharge_capacity",title:"维护放电容量（%）",width:150 },
                                    { "data": "ups_maintain_date",title:"维护到期日",width:150 },
                                    { "data": "ups_vender_phone",title:"厂家联系电话",width:120 },
                                    { "data": "ups_service",title:"服务商名称",width:120 },
                                    { "data": "ups_vender",title:"联系人",width:120 },
                                    { "data": "ups_service_phone",title:"服务商电话",width:100 },
                                    { "data": "remark",title:"备注",width:150 },
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
                                "fixedColumns": {leftColumns: 1},
                                "columns": [

                                    {data:'bms_company',title:'生产厂家',width:200},
                                    {data:'bms_device_addr',title:'厂家地址',width:200},
                                    {data:'bms_postcode',title:'邮编',width:200},
                                    {data:'bms_url',title:'支持网址',width:200},
                                    {data:'bms_tel',title:'支持固话',width:200},
                                    {data:'bms_phone',title:'手机',width:200},
                                    {data:'bms_service_phone',title:'服务商电话',width:200},
                                    {data:'bms_service_name',title:'服务商名称',width:200},
                                    {data:'bms_service_contact',title:'服务商联系人',width:200},
                                    {data:'bms_service_url',title:'服务商地址',width:300},
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
                                "fixedColumns": {leftColumns: 1},
                                "columns": [

                                    {data:'company_name',title:'单位名称',width:150},
                                    {data:'company_address',title:'单位地址',width:300},
                                    {data:'area_name',title:'管辖'},
                                    {data:'parent_area',title:'隶属',width:100,},
                                    {data:'supervisor_name',title:'主管领导姓名',width:80},
                                    {data:'supervisor_phone',title:'主管领导电话',width:100},
                                    {data:'owner',title:'部门负责人',width:80},
                                    {data:'owner_phone',title:'部门负责人电话',width:100},
                                    {data:'longitude',title:'经度',width:80},
                                    {data:'latitude',title:'纬度',width:80},
                                    {data:'station_num',title:'所辖站点个数',width:80},
                                    {data:'area_level',title:'隶属层级',width:100},
                                    {data:'network_type',title:'联网方式',width:100},
                                    {data:'bandwidth',title:'网络带宽',width:100},
                                    {data:'ipaddr',title:'IP地址',width:100},
                                    {data:'computer_brand',title:'上位机品牌',width:100},
                                    {data:'computer_os',title:'上位机操作系统',width:100},
                                    {data:'computer_conf',title:'主机配置',width:100},
                                    {data:'browser_name',title:'浏览器名称',width:100},
                                    {data:'server_capacity',title:'服务器容量',width:100},
                                    {data:'server_type',title:'服务器型号',width:100},
                                    {data:'cloud_address',title:'云空间地址',width:100},
                                    {data:'backup_period',title:'数据备份周期',width:100},
                                    {data:'backup_type',title:'数据备份方式',width:100},
                                    {data:'supervisor_depname',title:'监控部门名称',width:100},
                                    {data:'monitor_name1',title:'监控部门负责人',width:100},
                                    {data:'monitor_phone1',title:'监控部门负责人电话',width:100},
                                    {data:'monitor_name2',title:'其他联系人',width:100},
                                    {data:'monitor_phone2',title:'其他联系人电话',width:100},
                                    {data:'monitor_name3',title:'其他联系人',width:100},
                                    {data:'monitor_phone3',title:'其他联系人电话',width:100},
                                    {data:'monitor_tel1',title:'监控部门固定电话1',width:100},
                                    {data:'monitor_tel2',title:'监控部门固定电话2',width:100},

                                    {data:'duty_status',title:'值守状态/班次',width:80},
                                    {data:'manage',title:'管理员',width:80},
                                    {data:'viewer',title:'观察员',width:80},

                                    { "data": "remark",title:"备注",width:150 },
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
                        this.clearTables();
                        $("#addRole").hide();
                        $('#lock').hide();
                        $('#auto').width('100%');
                        this.listPlugin.push($('#auto table').DataTable( $.extend(true,{
                         "data": this.data,
                         "scrollX":ui.getListWidth(),
                         "columns": [
                            {"data": "", title: "序号"},
                            {"data": "id", title: "编号"},
                            {"data": "rolename", title: "角色名称"}
                         ]
                         },dataTableDefaultOption)));
                        return this;
                    }
                }
            },
            //人员列表
            "manager_personal":{
                extObj:{
                    events:{
                        "click .list-edit-btn":"onEdit",
                        "click .list-del-btn":"onDel"
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
                                "fixedColumns": {leftColumns: 1},
                                "columns": [
                                    {"data": "unit", title: "单位名称"},
                                    {"data": "name", title: "姓名"},
                                    {"data": "username", title: "登陆名"},
                                    {"data": "phone", title: "联系电话"},
                                    {"data": "backup_phone", title: "备用电话"},
                                    {"data": "email", title: "邮箱"},
                                    {"data": "postname", title: "职位"},
                                    {"data": "duty_num", title: "班次"},
                                    {"data": "location", title: "住址"},
                                    {"data": "areaname", title: "管理范围", render:function(data){
                                        var a = data.split(" ");
                                        if(a.length > 1)
                                            a.shift();
                                        return "<div>"+a.join(" ")+"</div>";
                                    }},
                                    {"data": "rolename", title: "角色"},

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
                        "change .changed": "onChange",
                        "mouseover .dataTable tr":"inRow",
                        "mouseout .dataTable tr":"inRow"
                    },
                    onChange: function(e){
                        var field = $(e.currentTarget).attr('field');
                        var id = $(e.currentTarget).attr("dataid");
                        var val = $(e.currentTarget).val();
                        console.log(field,id,val);
                        API.updateMessage({id:id, field:field,val:val});
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
                                "fixedColumns": {leftColumns: 1},
                                "columns": [
                                    {"data": "id", title: "ID"},
                                    {"data": "desc", title: "描述", width:200},
                                    {"data": "en", title: "编码"},
                                    {"data": "ignore", title: "是否可忽略", width:100,render: function(data,_,allDate){
                                            var html = [];
                                            html.push('<select class="changed" field="ignore" dataid='+allDate.id+'>');
                                            if(data == '1'){
                                                html.push('<option value=1 selected>是</option>');
                                                html.push('<option value=0>否</option>');
                                            }else{
                                                html.push('<option value=1>是</option>');
                                                html.push('<option value=0 selected>否</option>');
                                            }
                                            html.push('</select>');
                                            return html.join("");
                                        }},
                                    {"data": "send_email", title: "是否发送邮件",width:100,
                                        render: function(data,_,allDate){
                                            var html = [];
                                            html.push('<select class="changed" field="send_email" dataid='+allDate.id+'>');
                                            if(data == '1'){
                                                html.push('<option value=1 selected>是</option>');
                                                html.push('<option value=0>否</option>');
                                            }else{
                                                html.push('<option value=1>是</option>');
                                                html.push('<option value=0 selected>否</option>');
                                            }
                                            html.push('</select>');
                                            return html.join("");
                                        }
                                    },
                                    {"data": "send_msg", title: "是否发送短信",width:100,render: function(data,_,allDate){
                                            var html = [];
                                            html.push('<select class="changed" field="send_msg" dataid='+allDate.id+'>');
                                            if(data == '1'){
                                                html.push('<option value=1 selected>是</option>');
                                                html.push('<option value=0>否</option>');
                                            }else{
                                                html.push('<option value=1>是</option>');
                                                html.push('<option value=0 selected>否</option>');
                                            }
                                            html.push('</select>');
                                            return html.join("");
                                        }},
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
                        var roleid = JSON.parse(localStorage.getItem("userinfo")).role;
                        if(roleid == 1){
                            ui.showStationOptionEditDialog($(e.currentTarget).attr('pid'));
                        }else{
                            alert('您无编辑权限')
                        }
                    },
                    fetchData:function(_param){
                        var _param = {};
                        var navData = nav.getSites();
                        var ids;

                        if(this.ids && this.ids.sid){
                            ids = this.ids.sid;
                        }else{
                            ids = navData.ids.join(",");
                        }
                        console.log(navData);
                        $.extend(_param,{id:ids});
                        API.getSationOptionsData(_param);
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
                                "fixedColumns": {leftColumns: 3},
                                "columns": [
                                    {"data": "sn_key", title: "物理地址",width:100},
                                    {"data": "site_name", title: "站名称",width:100},
                                    {"data": "sid", title: "站号",width:100},
                                    {"data": "Groups", title: "站内组数",width:100},
                                    {"data": "GroBats", title: "每组电池数",width:100},
                                    {"data": "CurSensor", title: "电流传感安装状态",width:100},
                                    {"data": "Time_MR", title: "内阻测量间隔",width:100},
                                    {"data": "SampleInt", title: "采集间隔",width:100},
                                    {"data": "MaxTem_R", title: "高温报警_红",width:100},
                                    {"data": "MaxTem_O", title: "高温报警_橙",width:100},
                                    {"data": "MaxTem_Y", title: "高温报警_黄",width:100},
                                    {"data": "MinTem_R", title: "低温报警_红",width:100},
                                    {"data": "MinTem_O", title: "低温报警_橙",width:100},
                                    {"data": "MinTem_Y", title: "低温报警_黄",width:100},
                                    {"data": "MaxHum_R", title: "高湿报警_红",width:100},
                                    {"data": "MaxHum_O", title: "高湿报警_橙",width:100},
                                    {"data": "MaxHum_Y", title: "高湿报警_黄",width:100},
                                    {"data": "MinHum_R", title: "低湿报警_红",width:100},
                                    {"data": "MinHum_O", title: "低湿报警_橙",width:100},
                                    {"data": "MinHum_Y", title: "低湿报警_黄",width:100},
                                    {"data": "CurRange", title: "站电流量程",width:100},
                                    {"data": "KI", title: "站电流系数",width:100},
                                    {"data": "ZeroCurADC", title: "站电流零位",width:100},
                                    {"data": "DisChaLim_R", title: "放电报警_红",width:100},
                                    {"data": "DisChaLim_O", title: "放电报警_橙",width:100},
                                    {"data": "DisChaLim_Y", title: "放电报警_黄",width:100},
                                    {"data": "ChaLim_R", title: "充电报警_红",width:100},
                                    {"data": "ChaLim_O", title: "充电报警_橙",width:100},
                                    {"data": "ChaLim_Y", title: "充电报警_黄",width:100},
                                    {"data": "HiVolLim_R", title: "高压报警_红",width:100},
                                    {"data": "HiVolLim_O", title: "高压报警_橙",width:100},
                                    {"data": "HiVolLim_Y", title: "高压报警_黄",width:100},
                                    {"data": "LoVolLim_R", title: "低压报警_红",width:100},
                                    {"data": "LoVolLim_O", title: "低压报警_橙",width:100},
                                    {"data": "LoVolLim_Y", title: "低压报警_黄",width:100},
                                    {"data": "ChaCriterion", title: "站充放电判据",width:100},


                                    {
                                        data:"sn_key",
                                        render: function (data) {
                                            var roleid = JSON.parse(localStorage.getItem('userinfo')).role;

                                            if(roleid!=1){
                                                return "";
                                            }
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
                        var roleid = JSON.parse(localStorage.getItem("userinfo")).role;
                        if(roleid == 1){
                            ui.showGroupOptionEditDialog($(e.currentTarget).attr('pid'));
                        }else{
                            alert('您无编辑权限')
                        }
                    },
                    fetchData:function(_param){
                        _param = _param || {};
                        var navData = nav.getSites();
                        var ids;

                        if(this.ids && this.ids.sid){
                            ids = this.ids.sid;
                        }else{
                            ids = navData.ids.join(",");
                        }
                        console.log(navData);
                        $.extend(_param,{id:ids});
                        API.getGroupOptionData(_param);
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
                                "fixedColumns": {leftColumns: 2},
                                "columns": [

                                    {"data": "site_name", title: "站名称",width:100},
                                    {"data": "sid", title: "站号",width:100},
                                    {"data": "GroBatNum", title: "每组电池数",width:100},
                                    {"data": "CurRange", title: "组电流量程",width:100},
                                    {"data": "KI", title: "组电流系数",width:100},
                                    {"data": "ZeroCurADC", title: "组电流零位",width:100},
                                    {"data": "DisChaLim_R", title: "组放电电流超上限_红",width:100},
                                    {"data": "DisChaLim_O", title: "组放电电流将达上限_橙",width:100},
                                    {"data": "DisChaLim_Y", title: "组放电电流偏高_黄",width:100},
                                    {"data": "ChaLim_R", title: "组充电电流超上限_红",width:100},
                                    {"data": "ChaLim_O", title: "组充电电流将达上限_橙",width:100},
                                    {"data": "ChaLim_Y", title: "组充电电流偏高_黄",width:100},
                                    {"data": "MaxTem_R", title: "组温度超上限_红",width:100},
                                    {"data": "MaxTem_O", title: "组温度将达上限_橙",width:100},
                                    {"data": "MaxTem_Y", title: "组温度偏高_黄",width:100},
                                    {"data": "MinTem_R", title: "组温度超下限_红",width:100},
                                    {"data": "MinTem_O", title: "组温度将达下限_橙",width:100},
                                    {"data": "MinTem_Y", title: "组温度偏低_黄",width:100},
                                    {"data": "ChaCriterion", title: "组充放电判据",width:100},
                                    {
                                        data:"sn_key",
                                        render: function (data) {
                                            var roleid = JSON.parse(localStorage.getItem('userinfo')).role;

                                            if(roleid!=1){
                                                return "";
                                            }
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
                        var roleid = JSON.parse(localStorage.getItem("userinfo")).role;
                        if(roleid == 1){
                            ui.showBatteryOptionEditDialog($(e.currentTarget).attr('pid'));
                        }else{
                            alert('您无编辑权限')
                        }
                    },
                    fetchData:function(_param){
                        _param = _param || {};
                        var navData = nav.getSites();
                        var ids;

                        if(this.ids && this.ids.sid){
                            ids = this.ids.sid;
                        }else{
                            ids = navData.ids.join(",");
                        }
                        console.log(navData);
                        $.extend(_param,{id:ids});
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
                                "fixedColumns": {leftColumns: 2},
                                "columns": [
                                    {"data": "site_name", title: "站名称",width:100},
                                    {"data": "sid", title: "站号",width:100},
                                    {"data": "KV", title: "电压系数",width:100},
                                    {"data": "KT", title: "温度系数",width:100},
                                    {"data": "KI", title: "激励电流系数",width:100},
                                    {"data": "T0", title: "T0校准温度",width:100},
                                    {"data": "ADC_T0", title: "T0温度码",width:100},
                                    {"data": "T1", title: "T1校准温度",width:100},
                                    {"data": "ADC_T1", title: "T1温度码",width:100},
                                    {"data": "MaxU_R", title: "电池电压超上限_红",width:100},
                                    {"data": "MaxU_O", title: "电池电压将达上限_橙",width:100},
                                    {"data": "MaxU_Y", title: "电池电压偏高_黄",width:100},
                                    {"data": "MinU_R", title: "电池电压超下限_红",width:100},
                                    {"data": "MinU_O", title: "电池电压将达下限_橙",width:100},
                                    {"data": "MinU_Y", title: "电池电压偏低_黄",width:100},
                                    {"data": "MaxT_R", title: "电池温度超上限_红",width:100},
                                    {"data": "MaxT_O", title: "电池温度将达上限_橙",width:100},
                                    {"data": "MaxT_Y", title: "电池温度偏高_黄",width:100},
                                    {"data": "MinT_R", title: "电池温度超下限_红",width:100},
                                    {"data": "MinT_O", title: "电池温度将达下限_橙",width:100},
                                    {"data": "MinT_Y", title: "电池温度偏低_黄",width:100},
                                    {"data": "MaxR_R", title: "电池内阻超上限_红",width:100},
                                    {"data": "MaxR_O", title: "电池内阻将达上限_橙",width:100},
                                    {"data": "MaxR_Y", title: "电池内阻偏高_黄",width:100},
                                    {"data": "MaxDevU_R", title: "电池电压偏差_红",width:100},
                                    {"data": "MaxDevU_O", title: "电池电压偏差_橙",width:100},
                                    {"data": "MaxDevU_Y", title: "电池电压偏差_黄",width:100},
                                    {"data": "MaxDevT_R", title: "电池温度偏差_红",width:100},
                                    {"data": "MaxDevT_O", title: "电池温度偏差_橙",width:100},
                                    {"data": "MaxDevT_Y", title: "电池温度偏差_黄",width:100},
                                    {"data": "MaxDevR_R", title: "电池内阻偏差_红",width:100},
                                    {"data": "MaxDevR_O", title: "电池内阻偏差_橙",width:100},
                                    {"data": "MaxDevR_Y", title: "电池内阻偏差_黄",width:100},
                                    {
                                        data:"sn_key",
                                        render: function (data) {
                                            var roleid = JSON.parse(localStorage.getItem('userinfo')).role;

                                            if(roleid!=1){
                                                return "";
                                            }
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
                                "data": _this.data,
                                "language": {
                                    "emptyTable": "站点数据为空"
                                },
                                "scrollX": ui.getListHeight(),
                                "scrollY": ui.getListHeight(),
                                "fixedColumns": {leftColumns: 1},
                                "columns": [

                                    {"data": "sid", title: "站号", width: 100},
                                    {"data": "site_name", title: "站点简称", width: 200},
                                    {"data": "serial_number", title: "物理地址", width: 250},
                                    {
                                        data: "serial_number",
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
                                "fixedColumns": {leftColumns: 1},
                                "columns": [

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
                    fetchData:function(){
                        var type = $("#cationCategory").val();
                        var param = {page:this.curPage,start:$('#beginTime').val()?+new Date($('#beginTime').val()):"", end: $('#endTime').val()?+new Date($('#endTime').val()):""};
                        if(type > 0){
                            param.type = type;
                        }
                        API.getGerneralalarmlog(param);
                    },
                    downloadUrl:"/api/index.php/gerneralalarm",
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
                                { "data": "alarm_sn",title:"序号" },
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
                                { "data": "alarm_suggestion",title:"建议处理方式" },
                                // {
                                //     "data": "alarm_sn",
                                //     title:"处理连接",
                                //     render: function (data,type,itemData) {
                                //         return _.template('<a class="resolveBtn" pid="<%=id%>" suggestion="<%=suggestion%}">未处理</a>')({
                                //             id:data,
                                //             suggestion:itemData.alarm_suggestion
                                //         });
                                //     }
                                // },
                                //{ "data": "alarm_process_and_memo",title:"处理过程、时间、管理员" }
                            ]
                        }, dataTableDefaultOption)));
                    }
                }
            },
            //报表：电池使用年限
            "batteryLife":{
                extObj:{
                    fetchData:function(_param){

                        var param = {page:this.curPage,start:$('#beginTime').val()?+new Date($('#beginTime').val()):"", end: $('#endTime').val()?+new Date($('#endTime').val()):""};
                        API.getByearlog(param);
                    },
                    downloadUrl:"/api/index.php/report/byearlog",
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

                                {"data": "brand", title: "品牌", width: 100},
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
            //报表：偏离趋势报表
            "deviationTrend":{
                extObj:{
                    fetchData:function(_param){
                        var param = {page:this.curPage, start:$('#beginTime').val()?+new Date($('#beginTime').val()):"", end: $('#endTime').val()?+new Date($('#endTime').val()):""};

                        API.getDeviationTrend(param);
                    },
                    downloadUrl:"/api/index.php/report/deviationTrend",
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

                                {"data": "site_name", title: "站名", width: 100},
                                {"data": "sid", title: "站号", width: 100},
                                {"data": "record_time", title: "时间", width: 100},
                                {"data": "avgU", title: "电压均值", width: 100,render:function(data){
                                    if(!data){
                                        return "";
                                    }
                                    return parseFloat(data).toFixed(2);
                                }},
                                {"data": "avgT", title: "温度均值", width: 100,render:function(data){
                                    if(!data){
                                        return "";
                                    }
                                    return parseFloat(data).toFixed(2);
                                }},
                                {"data": "avgR", title: "内阻均值", width: 100,render:function(data){
                                    if(!data){
                                        return "";
                                    }
                                    return parseFloat(data).toFixed(2);
                                }},
                                {"data": "avgU", title: "电压偏离度(%)",render:function(data){
                                    if(!data){
                                        return "";
                                    }
                                    return (Math.abs(0.3-data)/0.3*100).toFixed(2);
                                }},
                                {"data": "avgT", title: "温度偏离度(%)",render:function(data){
                                    if(!data){
                                        return "";
                                    }
                                    return (Math.abs(3-data)/3*100).toFixed(2);
                                }},
                                {"data": "avgR", title: "内阻偏离度(%)",render:function(data){
                                    if(!data){
                                        return "";
                                    }
                                    return (Math.abs(5-data)/5*100).toFixed(2);
                                }}
                            ]
                        }, dataTableDefaultOption)));
                    }
                }
            } ,
            //报表：充放电统计表
            "chargeOrDischarge":{
                extObj:{
                    fetchData:function(_param){
                        var param = {page:this.curPage,start:$('#beginTime').val()?+new Date($('#beginTime').val()):"", end: $('#endTime').val()?+new Date($('#endTime').val()):""};
                        API.getChargeOrDischarge(_param);
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
                                {"data": "time", title: "序号", width: 100},
                                {"data": "record_time", title: "记录时间", width: 200},
                                {"data": "sid", title: "站点id",width:100},
                                {"data": "site_name", title: "站点名称", width: 300},
                                {"data": "BBbCharge", title: "充电状态", width: 100, render: function(data){
                                    return data == 1 ?'是':'否'
                                }},
                                {"data": "BCbDisCharge", title: "放电状态", render: function(data){
                                    return data == 1 ?'是':'否'
                                }},
                            ]
                        }, dataTableDefaultOption)));
                    }
                }
            } ,
            // 报表：UI日志：设置
            "reportUilog_options":{
                extObj:{
                    fetchData:function(_param){
                        API.getUserlog({page:this.curPage,type:'2', start:$('#beginTime').val()?+new Date($('#beginTime').val()):"", end: $('#endTime').val()?+new Date($('#endTime').val()):""})
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
                                {"data": "username", title: "用户", width: 100},
                                {"data": "content", title: "操作内容",render:function(_,__,data){
                                    if(!data.newvalue){
                                        return data.content;
                                    }
                                    var json = JSON.parse(data.newvalue);
                                    if(json.site_name){
                                        var site = json.site_name;
                                        site = unescape(site.replace(/(u[a-z0-9]{4,4})/g,"%$1"));
                                        console.log(site);
                                        return data.content+"(站名:"+site+")";
                                    }else{
                                        return data.content
                                    }
                                }},
                                {"data": "modify_time", title: "操作时间", width: 150}
                            ]
                        }, dataTableDefaultOption)));
                    },
                    downloadUrl:"/api/index.php/userlog?type=2"
                }
            }
        };
    //报表：UI日志：用户登录登出
    listConfig.reportUilog_user = $.extend(true,{},listConfig.reportUilog_options,{
        extObj:{
            fetchData:function(_param){
                API.getUserlog({page:this.curPage,type:'1', start:$('#beginTime').val()?+new Date($('#beginTime').val()):"", end: $('#endTime').val()?+new Date($('#endTime').val()):""})
            },
            downloadUrl:"/api/index.php/userlog?type=1"
        }
    })
    //报表：UI日志：其他
    listConfig.reportUilog_other = $.extend(true,{},listConfig.reportUilog_options,{
        extObj:{
            fetchData:function(_param){
                API.getUserlog({page:this.curPage,type:'3', start:$('#beginTime').val()?+new Date($('#beginTime').val()):"", end: $('#endTime').val()?+new Date($('#endTime').val()):""})
            },
            downloadUrl:"/api/index.php/userlog?type=3"
        }
    })


    //查询：UI日志：设置
    listConfig.uilog_options = $.extend(true,{},listConfig.reportUilog_options,{
        extObj:{
            getNavData:function(){
                return nav.getSites();
            },
            fetchData:function(_param){
                API.getUserlog({page:this.curPage,type:'2', start:$('#beginTime').val()?+new Date($('#beginTime').val()):"", end: $('#endTime').val()?+new Date($('#endTime').val()):""})
            },
            downloadUrl:"/api/index.php/userlog?type=2"
        }
    })
    //查询：UI日志：用户登录登出
    listConfig.uilog_user = $.extend(true,{},listConfig.reportUilog_user,{
        extObj:{
            fetchData:function(_param){
                API.getUserlog({page:this.curPage,type:'1', start:$('#beginTime').val()?+new Date($('#beginTime').val()):"", end: $('#endTime').val()?+new Date($('#endTime').val()):""})
            },
            downloadUrl:"/api/index.php/userlog?type=1"
        }
    })
    //查询：UI日志：其他
    listConfig.uilog_other = $.extend(true,{},listConfig.reportUilog_other,{
        extObj:{
            fetchData:function(_param){
                API.getUserlog({page:this.curPage,type:'3', start:$('#beginTime').val()?+new Date($('#beginTime').val()):"", end: $('#endTime').val()?+new Date($('#endTime').val()):""})
            },
            downloadUrl:"/api/index.php/userlog?type=3"
        }
    })
    //查询：站
    listConfig.qureyStation = $.extend(true,{},listConfig.station,{
        extObj:{
            extEvent:function(){
                var _this = this;
                _this.listenTo(Backbone.Events,"qurey_stationColsChange",function(data){
                    console.log('query station col change');
                    _this.destoryPlugin();
                    _this.render(1);
                });
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
                if($('#beginTime').val() == ""){
                    alert('请选择时间');
                    return;
                }
                if($('#startTime').val() == ""){
                    alert('请选择时间');
                    return;
                }
                API.getStationHistoryData({id:ids,page:this.curPage,start:$('#beginTime').val()?+new Date($('#beginTime').val()):"", end: $('#endTime').val()?+new Date($('#endTime').val()):""})
            },
            downloadUrl:"/api/index.php/query/",

        }
    })
    //查询：组
    listConfig.qureyGroup = $.extend(true,{},listConfig.group,{
        extObj:{
            extEvent:function(){
                var _this = this;
                _this.listenTo(Backbone.Events,"qurey_groupColsChange",function(data){
                    _this.destoryPlugin();
                    _this.render(1);
                });
            },
            fetchData:function(_param){
                var _param = {};
                // var navData = nav.getSites();
                var ids;

                var navData = nav.getGroups();

                if($('#beginTime').val() == ""){
                    alert('请选择时间');
                    return;
                }
                if($('#startTime').val() == ""){
                    alert('请选择时间');
                    return;
                }
                API.getGroupHistoryData({id:navData.ids.join(","),page:this.curPage,start:$('#beginTime').val()?+new Date($('#beginTime').val()):"", end: $('#endTime').val()?+new Date($('#endTime').val()):""})
            },
            downloadUrl:"/api/index.php/query/groupmodule",
        }
    })
    //查询：电池
    listConfig.qureyBattery = $.extend(true,{},listConfig.battery,{

        extObj:{
            extEvent:function(){
                var _this = this;
                _this.listenTo(Backbone.Events,"qurey_batteryColsChange",function(data){
                    _this.destoryPlugin();
                    _this.render(1);
                });
            },
            fetchData:function(_param){
                var _param = {};
                var navData = nav.getBatteryIds();
                var ids;

                if(this.ids && this.ids.sid){
                    ids = this.ids.sid;
                }else{
                    ids = navData.ids.join(",");
                }

                if($('#beginTime').val() == ""){
                    alert('请选择时间');
                    return;
                }
                if($('#startTime').val() == ""){
                    alert('请选择时间');
                    return;
                }
                API.getBatteryHistoryData({id:ids,page:this.curPage,start:$('#beginTime').val()?+new Date($('#beginTime').val()):"", end: $('#endTime').val()?+new Date($('#endTime').val()):""})
            },
            refresh:function(){
                this.fetchData();
            },
            downloadUrl:"/api/index.php/query/batterymodule",
        }
    })

    //查询：数据报警查询
    listConfig.qureyCaution = $.extend(true,{},listConfig.caution,{

        extObj:{
            fetchData:function(_param){
                var _param = {};
                var navData = nav.getBatteryIds();
                var ids;

                if(this.ids && this.ids.sid){
                    ids = this.ids.sid;
                }else{
                    ids = navData.ids.join(",");
                }

                if($('#beginTime').val() == ""){
                    alert('请选择时间');
                    return;
                }
                if($('#startTime').val() == ""){
                    alert('请选择时间');
                    return;
                }
                var cautionType = $("#cationCategory").val();
                API.getCautionsData({cautionType:cautionType,type:1,id:ids,page:this.curPage,start:$('#beginTime').val()?+new Date($('#beginTime').val()):"", end: $('#endTime').val()?+new Date($('#endTime').val()):""})
            },
            refresh:function(){
                this.fetchData();
            },
            downloadUrl:"/api/index.php/query/batterymodule",
            render:function(){
                //this.destoryPlugin();
                var _this = this;
                //ui.resizeAutoListWidth();
                if(_this.listPlugin && _this.listPlugin[0]){
                    _this.updateList();
                    _this.updateAtype()
                }else{
                    _this.updateAtype()
                    this.listPlugin.push($('#auto table').DataTable( $.extend(true,{},dataTableDefaultOption,{
                        "data": this.data,
                        "paging":false,
                        "scrollX":ui.getListHeight(),
                        //"scrollY":ui.getListHeight(),
                        "columns": [

                            { "data": "site_name", "title":"站名",width:150,"sClass":"site_name_left",render:function(data,type,itemData){
                                    return "<div class='show_station_detail' style='color:blue;cursor:pointer;' id='"+(itemData.sn_key.substring(0,10)+"0000")+"'>"+data+"</div>"
                            }},
                            { "data": "sn_key",title:"站号",width:80, render:function(data, type,itemData){
                                return itemData.sid;
                            }},
                            { "data": "sn_key",title:"组号",width:80, render:function(data, type,itemData){
                                if(itemData.type == "battery" || itemData.type == "group"){
                                    return itemData.sn_key.substring(10,12);
                                }else{
                                    return "";
                                }

                            }  },//组序列号
                            { "data": "sn_key",title:"电池号",width:80, render:function(data, type,itemData){
                                if(itemData.type == "battery")
                                    return itemData.sn_key.substring(12,14);
                                else{
                                    return "";
                                }
                            }},
                            { "data": "time",title:"时间" ,width:200},
                            { "data": "desc",title:"警情内容",width:200,render:function(data, type, itemData){
                                var desc = itemData.desc;
                                var color="";
                                if(desc.indexOf("橙") > -1){
                                    color="#e7691d"
                                }else if(desc.indexOf("黄") > -1){
                                    color="#fabd05"
                                }else{
                                    color="#f20f0f"
                                }
                                return "<div style='color:"+color+"'>"+data+"</div>";
                            }},
                            { "data": "current",title:"数值",width:80,render:function(data, type, itemData){
                                var desc = itemData.desc;
                                var color="";
                                if(desc.indexOf("橙") > -1){
                                    color="#e7691d"
                                }else if(desc.indexOf("黄") > -1){
                                    color="#fabd05"
                                }else{
                                    color="#f20f0f"
                                }
                                return "<div style='font-weight:bold;color:"+color+"'>"+data+"</div>";
                            }},
                            { "data": "markup",title:"操作记录",render:function(data,type,itemData){
                                var a = data == null ? "": data;
                                if(itemData.status == 2){
                                    return '<div style="text-align:left;">已忽略</div>';
                                }
                                return '<div style="text-align:left;">'+a+'</div>';
                            }},
                            { "data": "contact",title:"操作人",width:80},
                            { "data": "markuptime",title:"操作时间",width:200},
                            //{ "data": "alarm_process_and_memo",title:"处理过程、时间、管理员" }
                        ]
                    })));
                }

                // _this.checkAllRows();
                return this;
            }
        }
    })

    

    //查询：强采内阻
    listConfig.IRCollect = $.extend(true,{},listConfig.station,{
        extObj:{
            fetchData:function(_param){
                var _param = {};
                _param.page=this.curPage;
                API.getIRCollectData(_param);
            },
            downloadUrl:"/api/index.php/query/groupmodule",
            extEvent:function(){
                var _this = this;
                this.listenTo(Backbone.Events,"rCollect:start",function(data){
                    common.loadTips.show("正在与站点联系采集相关电池内阻，请稍后");
                    setTimeout(function(){
                        common.loadTips.close();
                    },5000);
                    $('body').everyTime('2s','irc',function(){
                        console.log('get IRCollect');
                        _this.fetchData();
                    });
                });

                

                this.listenTo(Backbone.Events,"rCollect:start:fail",function(data){
                    common.loadTips.show("采集失败，请重试");
                    setTimeout(function(){
                        common.loadTips.close();
                    },5000)
                });
            },
            render: function(){
                var _this = this;
                //_this.destoryPlugin();
                if(_this.listPlugin && _this.listPlugin[0]){
                    _this.updateList();
                }else{
                    require(["fixedColumn"], function () {
                        _this.listPlugin.push($('#auto table').DataTable($.extend(true, {
                            "data": _this.data,
                            "language": {
                                "emptyTable": "采集内阻数据为空"
                            },
                            "scrollX": ui.getListHeight(),
                            "scrollY": ui.getListHeight(),
                            "fixedColumns": {leftColumns: 1},
                            "columns": [

                                {"data": "sid", title: "站号", width: 100},
                                {"data": "site_name", title: "站点简称", width: 200},
                                {"data": "stationid", title: "物理地址"},
                                {"data": "groupid", title: "组号"},
                                {"data": "batteryid", title: "电池号"},
                                {"data": "R", title: "内阻值"},
                                {"data": "collect_time", title: "完成时间"}// ,
                                //{"data": "collect_endtime", title: "完成时间"},
                            ]
                        }, dataTableDefaultOption)));
                    });
                }
            }
        }
    })

    //查询：强采内阻
    listConfig.adminConfig = $.extend(true,{},listConfig.station,{
        extObj:{
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
                                "fixedColumns": {leftColumns: 1},
                                "columns": [
                                    {"data": "unit", title: "单位名称"},
                                    {"data": "name", title: "姓名"},
                                    {"data": "username", title: "登陆名"},
                                    {"data": "phone", title: "联系电话"},
                                    {"data": "backup_phone", title: "备用电话"},
                                    {"data": "email", title: "邮箱"},
                                    {"data": "postname", title: "职位"},
                                    {"data": "duty_num", title: "班次"},
                                    {"data": "location", title: "住址"},
                                    {"data": "areaname", title: "管理范围", render:function(data){
                                        var a = data.split(" ");
                                        if(a.length > 1)
                                            a.shift();
                                        return "<div>"+a.join(" ")+"</div>";
                                    }},
                                    {"data": "rolename", title: "角色"}
                                ]
                            })));
                            _this.checkAllRows();
                        })
                        return this;
                    }
        }
    })


    //查询：门限
    listConfig.limitation = $.extend(true,{},listConfig.limitationSetting,{extObj:{
        
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
                    "fixedColumns": {leftColumns: 1},
                    "columns": [

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
        downloadUrl:"/api/index.php/query",
        getNavData:function(){
            return nav.getSites();
        },
        getNavData:function(){
            return nav.getSites();
        },
        fetchData:function(_param){
            var _param = {};
            var navData = nav.getSites();
            var ids;

            if(this.ids && this.ids.sid){
                ids = this.ids.sid;
            }else{
                ids = navData.ids.join(",");
            }
            console.log(navData);
            $.extend(_param,{id:ids});
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
                    "columns": [
                        { "data": "sid",title:"站号",width:80  },
                        {
                            "data": "site_name",
                            title:"站点简称",
                            width:100
                        },
                        { "data": "serial_number",title:"物理地址",width:100},
                        
                        {
                            "data": "site_name",
                            title:"站点全称",
                            width:100
                        },

                        { "data": "site_location",title:"站点地址",width:100  },
                        { "data": "site_property",title:"站点性质",width:150  },
                        { "data": "areaname",title:"隶属区域",width:150  },
                        { "data": "fix_phone",title:"固定电话"},
                        { "data": "functionary",title:"负责人",width:120,render:function(data,_,allData){
                            var html = [];
                            if(allData.functionary_sms == 1){
                                html.push('<span>')
                                html.push('<img src="/images/sms.png">');
                                html.push("</span>");
                            }
                            

                            if(allData.functionary_mail){
                                html.push('<span>')
                                html.push('<img src="/images/email.png">');
                                html.push("</span>");
                            }

                            return "<span>"+data+"</span>"+html.join("");
                        }},
                        { "data": "functionary_phone",title:"负责人电话",width:120},
                        { "data": "device_owner",title:"设备负责人",width:120},
                        { "data": "device_owner_phone",title:"设备负责人电话",width:120},

                        { "data": "emergency_person",title:"紧急联系人姓名",width:120 },
                        { "data": "emergency_phone",title:"紧急联系人手机",width:120  },
                        { "data": "area_owner",title:"区域主管",width:120,render:function(data,_,allData){
                            var html = [];
                            if(allData.area_owner_sms == 1){
                                html.push('<span>')
                                html.push('<img src="/images/sms.png">');
                                html.push("</span>");
                            }
                            

                            if(allData.area_owner_mail){
                                html.push('<span>')
                                html.push('<img src="/images/email.png">');
                                html.push("</span>");
                            }

                            return "<span>"+data+"</span>"+html.join("");
                        }},
                        { "data": "area_owner_phone",title:"区域主管电话",width:120},
                        { "data": "parent_owner",title:"上级主管",width:120,render:function(data,_,allData){
                            var html = [];
                            if(allData.parent_owner_sms == 1){
                                html.push('<span>')
                                html.push('<img src="/images/sms.png">');
                                html.push("</span>");
                            }
                            

                            if(allData.parent_owner_mail){
                                html.push('<span>')
                                html.push('<img src="/images/email.png">');
                                html.push("</span>");
                            }

                            return "<span>"+data+"</span>"+html.join("");
                        }},
                        { "data": "parent_owner_phone",title:"上级主管电话"},
                        { "data": "groups",title:"电池组数",width:100  },
                        { "data": "batteries",title:"每组电池数",width:80  },
                        { "data": "postal_code",title:"邮政编码",width:80  },
                        { "data": "site_latitude",title:"站点纬度",width:80  },
                        { "data": "site_longitude",title:"站点经度",width:80  },
                        { "data": "ipaddress",title:"IP地址",width:100  },
                        { "data": "ipaddress_method",title:"控制器IP地址或方式",width:150  },
                        { "data": "site_control_type",title:"站点控制器型号",width:100  },
                        { "data": "bms_install_date",title:"BMS系统安装日期",width:150  },
                        { "data": "group_collect_type",title:"组电流采集器型号",width:150  },
                        { "data": "group_collect_num",title:"组电流采集器数量",width:150  },
                        { "data": "inductor_brand",title:"互感器品牌",width:150  },
                        { "data": "group_collect_install_type",title:"组电流采集器安装模式",width:150  },
                        { "data": "battery_collect_type",title:"电池数据采集器型号",width:150  },
                        { "data": "battery_collect_num",title:"电池数据采集器数量",width:150  },
                        { "data": "humiture_type",title:"环境温湿度方式",width:150  },
                        { "data": "has_light",title:"灯光报警",width:80},
                        { "data": "has_speaker",title:"声音报警",width:80},
                        { "data": "has_sms",title:"短信",width:80},
                        { "data": "has_smart_control",title:"智能控制",width:80},
                        { "data": "has_group_TH_control",title:"温湿度传感器",width:150},
                        { "data": "has_group_HO_control",title:"氢氧气传感器",width:150},
                        { "data": "device_mac",title:"网口MAC",width:150},
                        { "data": "remark",title:"备注",width:150 },
                    ]
                },dataTableDefaultOption)));

            })
            return this;
        }
    }})
    //查询：基本信息：电池
    listConfig.baseinfo_queryBatterys = $.extend(true,{},listConfig.stationInfo_batterys,{extObj:{
        getNavData:function(){
            return nav.getSites();
        },
        fetchData:function(_param){
            var _param = {};
            var navData = nav.getSites();
            var ids;

            if(this.ids && this.ids.sid){
                ids = this.ids.sid;
            }else{
                ids = navData.ids.join(",");
            }
            console.log(navData);
            $.extend(_param,{id:ids});
            API.getBatteryInfosData(_param);
        },
        downloadUrl:"/api/index.php/query/batterymodule",
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
                    "fixedColumns": {leftColumns: 1},
                    "columns": [
                        { "data": "sid",title:"站号",width:100  },
                        { "data": "site_name",title:"站点简称",width:100  },
                        { "data": "battery_factory",title:"生产厂家",width:150  },
                        { "data": "battery_num",title:"电池型号",width:150  },
                        { "data": "battery_date",title:"生产日期",width:150  },
                        { "data": "battery_voltage",title:"标称电压（V）",width:150  },
                        { "data": "battery_oum",title:"标称内阻（MΩ）",width:150  },
                        { "data": "battery_dianrong",title:"电池标称容量（Ah）",width:150  },
                        { "data": "battery_float_voltage",title:"浮充标准电压（V）",width:150  },
                        { "data": "battery_max_current",title:"最大充电电流（A）",width:150  },
                        { "data": "battery_max_discharge_current",title:"最大放电电流（A）",width:150  },
                        { "data": "battery_float_up",title:"浮充电压上限（V）",width:150  },
                        { "data": "battery_float_dow",title:"电池浮充电压下限（V）",width:150  },
                        { "data": "battery_discharge_down",title:"放电电压下限（V）",width:150  },
                        { "data": "battery_scrap_date",title:"强制报废日期",width:150  },
                        { "data": "battery_life",title:"设计寿命（年）",width:150  },
                        { "data": "battery_column_type",title:"电池级柱类型",width:150  },
                        { "data": "battery_temperature",title:"温度要求（℃）",width:150  },
                        { "data": "battery_humidity",title:"湿度要求（%）",width:150  },
                        { "data": "battery_type",title:"电池种类",width:150  },
                        { "data": "battery_factory_phone",title:"电池厂家联系电话",width:150  },
                        { "data": "remark",title:"备注",width:150 },
                    ]
                },dataTableDefaultOption)));

            })
            return this;
        }
    }})
    //查询：基本信息：用户单位信息信息表
    listConfig.baseinfo_queryInstitutions = $.extend(true,{},listConfig.stationInfo_institutions,{extObj:{
        getNavData:function(){
            return nav.getSites();
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
                    "fixedColumns": {leftColumns: 1},
                    "columns": [
                        {data:'company_name',title:'单位名称',width:150},
                        {data:'company_address',title:'单位地址',width:300},
                        {data:'area_name',title:'管辖'},
                        {data:'parent_area',title:'隶属',width:100,},
                        {data:'supervisor_name',title:'主管领导姓名',width:80},
                        {data:'supervisor_phone',title:'主管领导电话',width:100},
                        {data:'owner',title:'部门负责人',width:80},
                        {data:'owner_phone',title:'部门负责人电话',width:100},
                        {data:'longitude',title:'经度',width:80},
                        {data:'latitude',title:'纬度',width:80},
                        {data:'station_num',title:'所辖站点个数',width:80},
                        {data:'area_level',title:'隶属层级',width:100},
                        {data:'network_type',title:'联网方式',width:100},
                        {data:'bandwidth',title:'网络带宽',width:100},
                        {data:'ipaddr',title:'IP地址',width:100},
                        {data:'computer_brand',title:'上位机品牌',width:100},
                        {data:'computer_os',title:'上位机操作系统',width:100},
                        {data:'computer_conf',title:'主机配置',width:100},
                        {data:'browser_name',title:'浏览器名称',width:100},
                        {data:'server_capacity',title:'服务器容量',width:100},
                        {data:'server_type',title:'服务器型号',width:100},
                        {data:'cloud_address',title:'云空间地址',width:100},
                        {data:'backup_period',title:'数据备份周期',width:100},
                        {data:'backup_type',title:'数据备份方式',width:100},
                        {data:'supervisor_depname',title:'监控部门名称',width:100},
                        {data:'monitor_name1',title:'监控部门负责人',width:100},
                        {data:'monitor_phone1',title:'监控部门负责人电话',width:100},
                        {data:'monitor_name2',title:'其他联系人',width:100},
                        {data:'monitor_phone2',title:'其他联系人电话',width:100},
                        {data:'monitor_name3',title:'其他联系人',width:100},
                        {data:'monitor_phone3',title:'其他联系人电话',width:100},
                        {data:'monitor_tel1',title:'监控部门固定电话1',width:100},
                        {data:'monitor_tel2',title:'监控部门固定电话2',width:100},

                        {data:'duty_status',title:'值守状态/班次',width:80},
                        {data:'manage',title:'管理员',width:80},
                        {data:'viewer',title:'观察员',width:80},

                        { "data": "remark",title:"备注",width:150 },
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
                    "fixedColumns": {leftColumns: 1},
                    "columns": [
                        {data:'bms_company',title:'生产厂家',width:200},
                        {data:'bms_device_addr',title:'厂家地址',width:200},
                        {data:'bms_postcode',title:'邮编',width:200},
                        {data:'bms_url',title:'支持网址',width:200},
                        {data:'bms_tel',title:'支持固话',width:200},
                        {data:'bms_phone',title:'手机',width:200},
                        {data:'bms_service_phone',title:'服务商电话',width:200},
                        {data:'bms_service_name',title:'服务商名称',width:200},
                        {data:'bms_service_contact',title:'服务商联系人',width:200},
                        {data:'bms_service_url',title:'服务商地址',width:300},
                        {data:'bms_version',title:'软件版本号',width:150},
                        {data:'bms_update_mark',title:'软件升级记录',width:170},
                        {data:'bms_mark',title:'备注',width:300},
                    ]
                },dataTableDefaultOption)));
            })
            return this;
        }
    }})

    //查询：基本信息：UPS信息表
    listConfig.baseinfo_queryUpsInfo = $.extend(true,{},listConfig.stationInfo_upsInfo,{extObj:{
        getNavData:function(){
            return nav.getSites();
        },
        fetchData:function(_param){
            var _param = {};
            var navData = nav.getSites();
            var ids;

            if(this.ids && this.ids.sid){
                ids = this.ids.sid;
            }else{
                ids = navData.ids.join(",");
            }
            console.log(navData);
            $.extend(_param,{id:ids});
            API.getUpsInfosData(_param);
        },
        render:function(){
            var _this = this;
            _this.destoryPlugin();
            $('#auto').width('100%');
            console.log(listConfig.stationInfo_upsInfo);
            require(["fixedColumn"],function() {
                _this.listPlugin.push($('#auto table').DataTable( $.extend(true,{
                    "data": _this.data,
                    "scrollX":ui.getListHeight(),
                    "scrollY":ui.getListHeight(),
                    "fixedColumns": {leftColumns: 1},
                    "columns": [
                        { "data": "sid",title:"站号",width:80 },
                        { "data": "site_name",title:"站名",width:250 },
                        { "data": "ups_factory",title:"生产厂家",width:250 },
                        { "data": "ups_type",title:"型号",width:100 },
                        { "data": "ups_create_date",title:"生产日期",width:150 },
                        { "data": "ups_install_date",title:"安装日期",width:150 },
                        { "data": "ups_power",title:"功率/容量（V/A）",width:150 },
                        { "data": "ups_power_in",title:"输入功率（V/A）",width:150 },
                        { "data": "ups_power_out",title:"输出功率（V/A）",width:150 },
                        { "data": "ups_battery_vol",title:"外接电池电压（V）",width:150 },
                        { "data": "ups_battery_current",title:"外接电池电流（A）",width:150 },
                        { "data": "ac_protect",title:"AC过流保护（V/A）",width:150 },
                        { "data": "dc_protect",title:"DC过流保护（V/A）",width:150 },
                        { "data": "on_net",title:"联网检测",width:150 },
                        { "data": "alarm_content",title:"报警内容",width:150 },
                        { "data": "discharge_protect",title:"放电保护值（%）",width:150 },
                        { "data": "redundancy_num",title:"冗余数量(台)",width:150 },
                        { "data": "floting_charge",title:"浮充电压（V）",width:150 },
                        { "data": "ups_vdc",title:"电压范围(V)",width:150 },
                        { "data": "ups_reserve_hour",title:"额定候备时间（W/H）",width:250 },
                        { "data": "ups_charge_mode",title:"充电方式",width:100 },
                        { "data": "ups_max_charge",title:"最大充电电流（A）",width:150 },
                        { "data": "ups_max_discharge",title:"最大放电电流（A）",width:150 },
                        { "data": "ups_period_days",title:"规定维护周期（天）",width:150 },
                        { "data": "ups_discharge_time",title:"维护放电时长（分钟）",width:150 },
                        { "data": "ups_discharge_capacity",title:"维护放电容量（%）",width:150 },
                        { "data": "ups_maintain_date",title:"维护到期日",width:150 },
                        { "data": "ups_vender_phone",title:"厂家联系电话",width:120 },
                        { "data": "ups_service",title:"服务商名称",width:120 },
                        { "data": "ups_vender",title:"联系人",width:120 },
                        { "data": "ups_service_phone",title:"服务商电话",width:100 },
                        { "data": "remark",title:"备注",width:150 },
                    ]
                },dataTableDefaultOption)));
            })
            return this;
        }
    }})


    //查询：参数：组
    listConfig.option_groupOption = $.extend(true,{},listConfig.optionSetting_groupOption,{extObj:{
        getNavData:function(){
            return nav.getSites();
        },
        extEvent: function(){
            var _this = this;
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
                    _this.fetchData();
                }
            });
        },
        fetchData:function(_param){
            var _param = {};
            var navData = nav.getSites();
            var ids;

            if(this.ids && this.ids.sid){
                ids = this.ids.sid;
            }else{
                ids = navData.ids.join(",");
            }
            console.log(navData);
            $.extend(_param,{id:ids});
            API.getGroupOptionData(_param);
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
                    "fixedColumns": {leftColumns: 1},
                    "columns": [
                            {"data": "site_name", title: "站名称",width:100},
                            {"data": "sid", title: "站号",width:100},
                            {"data": "GroBatNum", title: "每组电池数",width:100},
                            {"data": "CurRange", title: "组电流量程",width:100},
                            {"data": "KI", title: "组电流系数",width:100},
                            {"data": "ZeroCurADC", title: "组电流零位",width:100},
                            {"data": "DisChaLim_R", title: "组放电电流超上限_红",width:100},
                            {"data": "DisChaLim_O", title: "组放电电流将达上限_橙",width:100},
                            {"data": "DisChaLim_Y", title: "组放电电流偏高_黄",width:100},
                            {"data": "ChaLim_R", title: "组充电电流超上限_红",width:100},
                            {"data": "ChaLim_O", title: "组充电电流将达上限_橙",width:100},
                            {"data": "ChaLim_Y", title: "组充电电流偏高_黄",width:100},
                            {"data": "MaxTem_R", title: "组温度超上限_红",width:100},
                            {"data": "MaxTem_O", title: "组温度将达上限_橙",width:100},
                            {"data": "MaxTem_Y", title: "组温度偏高_黄",width:100},
                            {"data": "MinTem_R", title: "组温度超下限_红",width:100},
                            {"data": "MinTem_O", title: "组温度将达下限_橙",width:100},
                            {"data": "MinTem_Y", title: "组温度偏低_黄",width:100},
                            {"data": "ChaCriterion", title: "组充放电判据",width:100},
                    ]
                })));
            })
            return this;
        }
    }})
    //查询：参数：站
    listConfig.option_stationOption = $.extend(true,{},listConfig.optionSetting_stationOption,{extObj:{
        getNavData:function(){
            return nav.getSites();
        },
        extEvent: function(){
            var _this = this;
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
                    _this.fetchData();
                }
            });
        },
        fetchData:function(_param){
            var _param = {};
            var navData = nav.getSites();
            var ids;

            if(this.ids && this.ids.sid){
                ids = this.ids.sid;
            }else{
                ids = navData.ids.join(",");
            }
            console.log(navData);
            $.extend(_param,{id:ids});
            API.getSationOptionsData(_param);
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
                    "fixedColumns": {leftColumns: 1},
                    "columns": [
                                    {"data": "sn_key", title: "物理地址",width:100},
                                    {"data": "site_name", title: "站名称",width:100},
                                    {"data": "sid", title: "站号",width:100},
                                    {"data": "Groups", title: "站内组数",width:100},
                                    {"data": "GroBats", title: "每组电池数",width:100},
                                    {"data": "CurSensor", title: "电流传感安装状态",width:100},
                                    {"data": "Time_MR", title: "内阻测量间隔",width:100},
                                    {"data": "SampleInt", title: "采集间隔",width:100},
                                    {"data": "MaxTem_R", title: "高温报警_红",width:100},
                                    {"data": "MaxTem_O", title: "高温报警_橙",width:100},
                                    {"data": "MaxTem_Y", title: "高温报警_黄",width:100},
                                    {"data": "MinTem_R", title: "低温报警_红",width:100},
                                    {"data": "MinTem_O", title: "低温报警_橙",width:100},
                                    {"data": "MinTem_Y", title: "低温报警_黄",width:100},
                                    {"data": "MaxHum_R", title: "高湿报警_红",width:100},
                                    {"data": "MaxHum_O", title: "高湿报警_橙",width:100},
                                    {"data": "MaxHum_Y", title: "高湿报警_黄",width:100},
                                    {"data": "MinHum_R", title: "低湿报警_红",width:100},
                                    {"data": "MinHum_O", title: "低湿报警_橙",width:100},
                                    {"data": "MinHum_Y", title: "低湿报警_黄",width:100},
                                    {"data": "CurRange", title: "站电流量程",width:100},
                                    {"data": "KI", title: "站电流系数",width:100},
                                    {"data": "ZeroCurADC", title: "站电流零位",width:100},
                                    {"data": "DisChaLim_R", title: "放电报警_红",width:100},
                                    {"data": "DisChaLim_O", title: "放电报警_橙",width:100},
                                    {"data": "DisChaLim_Y", title: "放电报警_黄",width:100},
                                    {"data": "ChaLim_R", title: "充电报警_红",width:100},
                                    {"data": "ChaLim_O", title: "充电报警_橙",width:100},
                                    {"data": "ChaLim_Y", title: "充电报警_黄",width:100},
                                    {"data": "HiVolLim_R", title: "高压报警_红",width:100},
                                    {"data": "HiVolLim_O", title: "高压报警_橙",width:100},
                                    {"data": "HiVolLim_Y", title: "高压报警_黄",width:100},
                                    {"data": "LoVolLim_R", title: "低压报警_红",width:100},
                                    {"data": "LoVolLim_O", title: "低压报警_橙",width:100},
                                    {"data": "LoVolLim_Y", title: "低压报警_黄",width:100},
                                    {"data": "ChaCriterion", title: "站充放电判据",width:100}
                    ]
                })));
            })
            return this;
        }
    }})
    //查询：参数：电池
    listConfig.option_batteryOption = $.extend(true,{},listConfig.optionSetting_batteryOption,{extObj:{
        getNavData:function(){
            return nav.getSites();
        },
        extEvent: function(){
            var _this = this;
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
                    _this.fetchData();
                }
            });
        },
        fetchData:function(_param){
            var _param = {};
            var navData = nav.getSites();
            var ids;

            if(this.ids && this.ids.sid){
                ids = this.ids.sid;
            }else{
                ids = navData.ids.join(",");
            }
            console.log(navData);
            $.extend(_param,{id:ids});
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
                    "fixedColumns": {leftColumns: 2},
                    "columns": [
                        {"data": "site_name", title: "站名称",width:100},
                        {"data": "sid", title: "站号",width:100},
                        {"data": "KV", title: "电压系数",width:100},
                        {"data": "KT", title: "温度系数",width:100},
                        {"data": "KI", title: "激励电流系数",width:100},
                        {"data": "T0", title: "T0校准温度",width:100},
                        {"data": "ADC_T0", title: "T0温度码",width:100},
                        {"data": "T1", title: "T1校准温度",width:100},
                        {"data": "ADC_T1", title: "T1温度码",width:100},
                        {"data": "MaxU_R", title: "电池电压超上限_红",width:100},
                        {"data": "MaxU_O", title: "电池电压将达上限_橙",width:100},
                        {"data": "MaxU_Y", title: "电池电压偏高_黄",width:100},
                        {"data": "MinU_R", title: "电池电压超下限_红",width:100},
                        {"data": "MinU_O", title: "电池电压将达下限_橙",width:100},
                        {"data": "MinU_Y", title: "电池电压偏低_黄",width:100},
                        {"data": "MaxT_R", title: "电池温度超上限_红",width:100},
                        {"data": "MaxT_O", title: "电池温度将达上限_橙",width:100},
                        {"data": "MaxT_Y", title: "电池温度偏高_黄",width:100},
                        {"data": "MinT_R", title: "电池温度超下限_红",width:100},
                        {"data": "MinT_O", title: "电池温度将达下限_橙",width:100},
                        {"data": "MinT_Y", title: "电池温度偏低_黄",width:100},
                        {"data": "MaxR_R", title: "电池内阻超上限_红",width:100},
                        {"data": "MaxR_O", title: "电池内阻将达上限_橙",width:100},
                        {"data": "MaxR_Y", title: "电池内阻偏高_黄",width:100},
                        {"data": "MaxDevU_R", title: "电池电压偏差_红",width:100},
                        {"data": "MaxDevU_O", title: "电池电压偏差_橙",width:100},
                        {"data": "MaxDevU_Y", title: "电池电压偏差_黄",width:100},
                        {"data": "MaxDevT_R", title: "电池温度偏差_红",width:100},
                        {"data": "MaxDevT_O", title: "电池温度偏差_橙",width:100},
                        {"data": "MaxDevT_Y", title: "电池温度偏差_黄",width:100},
                        {"data": "MaxDevR_R", title: "电池内阻偏差_红",width:100},
                        {"data": "MaxDevR_O", title: "电池内阻偏差_橙",width:100},
                        {"data": "MaxDevR_Y", title: "电池内阻偏差_黄",width:100},
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
            listView = null;
            // $('#beginTime').val('');
            // $('#endTime').val('');
            //$("#list").html('');
        }
        var _listType = listType?listType+(sub?"_"+sub:""):'defaultConfig';
        var _listTpl = 'defaultListTpl';
        var _extObj= listConfig[_listType]?listConfig[_listType].extObj:{};
        _extObj = $.extend({},listConfig.defaultConfig.extObj,_extObj);
        $("#list").html($("#"+_listTpl).html());
        listView = new (Backbone.View.extend($.extend({},{el:$("#list"),ids:ids},_extObj)))();
        overFlag = false;
        var queryAndReport = [
            'qureyGroup',
            'qureyStation',
            'qureyBattery',
            'reportCaution',
            'deviationTrend',
            'chargeOrDischarge',
            'batteryLife',
            'reportUilog',
            'uilog'
        ];
        console.log('listType', listType);
        if(queryAndReport.indexOf(listType) > -1){
            if(checkSelectDate()){
                listView.fetchData();
            }
        }else{
            listView.fetchData();
        }
    }

    function checkSelectDate(){
        var startTime = $('#beginTime').val();
        var endTime = $("#endTime").val();
        if(startTime == "" || endTime == ""){
            return false;
        }
        return true;
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

                listView.destroy();
                listView = null;
                $("#list").html('');
            }
            return this;
        }
    };
})
