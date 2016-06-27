define(['require','api','stationsinfoDialog','context','ui','table'],function(require,API,stationInfoDialog,context,ui){
    var listView = null,
        dataTableDefaultOption = {
            "paging": false,
            "searching": false,
            "order": [ 1, 'asc' ],
            "info":false,
            "iDisplayLength":2
        },
        listConfig = {
            default:{
                extobj : {
                    data:null,
                    listPlugin:[],
                    el:'#list',
                    events:{
                        "click .dataTable tr":"selectRow",
                        "click .show-info":"openStationInfoDialog",
                        "mouseover .dataTable tr":"inRow",
                        "mouseout .dataTable tr":"outRow"
                    },
                    initialize:function(data){
                        var _this = this;
                        _this.listenTo(Backbone.Events,"listdata:update stationdata:get",function(data){
                            _this.data = data;
                            console.log("listdata:update stationdata:get");
                            _this.render().resetScrollBar();
                        });
                        _this.listenTo(Backbone.Events,"pageType:change",function(type){
                            initPage(type);
                            console.log("pageType:change");
                            _this.refresh();
                        });
                        _this.listenTo(Backbone.Events,"curstation:change",function(data){
                            console.log("curstation:change");
                            _this.refresh();
                        });
                    },
                    openStationInfoDialog:function(){
                        stationInfoDialog.show();
                    },
                    inRow:function(evt){
                        var _this = this,
                            data = _this.getRowData(evt.currentTarget);
                        $.each(_this.listPlugin,function(i,p){
                            $(p.rows().nodes()).removeClass('highlight')
                            $(p.rows().nodes()[data.index]).addClass('highlight');
                        })
                    },
                    outRow:function(evt){
                        var _this = this,
                            data = _this.getRowData(evt.currentTarget);
                        $.each(_this.listPlugin,function(i,p){
                            $(p.rows().nodes()[data.index]).removeClass('highlight');
                        })
                    },
                    triggerSelectEvent:function(){
                        //整理数据发送选择行事件
                        var _this = this,
                            selectedData = _this.listPlugin[0].rows('.selected').data(),
                            ids = [];
                        $.each(selectedData,function(i,d){
                            ids.push(d.id)
                        });
                        Backbone.Events.trigger('row:select',ids);
                    },
                    selectRow:function(evt){
                        var _this = this,
                            data = _this.getRowData(evt.currentTarget);
                        $.each(_this.listPlugin,function(i,p){
                            $(p.rows().nodes()[data.index]).toggleClass('selected');
                        })

                        _this.triggerSelectEvent();
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
                    resetScrollBar:function(){
                        $("#auto .dataTables_scrollBody,#auto .dataTables_scrollHead,#auto .dataTables_scroll,#auto .dataTables_wrapper").width($("#auto .dataTable").width());

                        $('#auto').mCustomScrollbar({
                            scrollButtons:{enable:true},
                            theme:"light-thick",
                            scrollbarPosition:"outside",
                            axis:"x",
                            autoExpandScrollbar:true,
                            advanced:{autoExpandHorizontalScroll:true}
                        });

                        ui.resizePage();
                        $("#xScrollBar").empty().append($("#auto .mCSB_scrollTools"));
                    },
                    refresh:function(){
                        var _this = this,
                            _param = _this.getParam();

                        if(_param){
                            _this.fetchData(_param);
                        }else{//TODO:获取参数失败

                        }
                    },
                    fetchData:function(_param){
                        API.getStationsData(_param);
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
                    render:function(){
                        this.listPlugin = [];
                        this.listPlugin.push($('#lock table').DataTable($.extend(true,{
                            "data": this.data,
                            "columnDefs":this.getColumnDefs(),
                            "columns": [
                                { "data": "name" },
                                { "data": "id" },
                                { "data": "time" }
                            ]
                        },dataTableDefaultOption) ));

                        ui.resizeAutoListWidth();

                        this.listPlugin.push($('#auto table').DataTable( $.extend(true,{
                            "data": this.data,
                            "columnDefs":this.getColumnDefs(),
                            "scrollX":true,
                            "columns": [
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
                        },dataTableDefaultOption)));
                        return this;
                    }
                }
            }
        }

    function initPage(listType){
        var _listType = listType || 'default';
        var _listTpl = (_listType == 'station'?'default':_listType)+'ListTpl';
        $("#list").html($("#"+_listTpl).html());

        require(["blocks/block-"+listType],function(extConfig){
            if(!listView){
                listConfig.default.el = $("#list");
                listView = new (Backbone.View.extend($.extend(true,extConfig.getExtObj(dataTableDefaultOption),listConfig.default.extobj)))();
            }else{
                listView = $.extend(true,listView,$.extend(true,{},extConfig.getExtObj(dataTableDefaultOption)));
            }
        })
    }
    return {
        init:function(listType){
            initPage(listType);

            stationInfoDialog.init();

            return this;
        }
    };
})