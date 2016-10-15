define(['require','api','context','table'],function(require,API,context){
    var view = null,
        stationId,
        config = {
            default:{
                extobj : {
                    data:null,
                    listPlugin:[],
                    el:$('#stationinfoTpl-dialog'),
                    initialize:function(data){
                        var _this = this;
                        _this.listenTo(Backbone.Events,"stationinfo:update",function(data){
                            _this.data = data.list;
                            _this.render();
                        });
                    },
                    jumpToRealTime:function(){
                        window.location.href="#/manage/station/"+stationId.substr(0,10);
                    },
                    refresh:function(){
                        var _this = this,
                            _param = _this.getParam();

                        if(_param){
                            _this.fetchData(_param);
                        }else{//TODO:获取参数失败

                        }
                    },
                    getParam:function(){
                        var curstation = context.getCurStations(),
                            listType = context.getListType();

                        return {
                            listType:listType
                        };
                    },
                    render:function(){
                        var _this = this,
                            $dialog = $("#stationinfoTpl-dialog").length?$("#stationinfoTpl-dialog").replaceWith($($("#stationinfoTpl").html())):$($("#stationinfoTpl").html());
                        $dialog.dialog({
                            modal:true,
                            show:300,
                            height:270,
                            width:900,
                            title:"站信息",
                            close:function(evt,ui){
                                $(this).dialog( "destroy" );
                                _this.table.destroy();
                            },
                            open:function(){
                                _this.table =  $('#stationinfoTpl-dialog table').DataTable( {
                                    "data": _this.data,
                                    "paging": false,
                                    "searching": false,
                                    "info":false,
                                    "retrieve":true,
                                    "scrollX":true,
                                    "scrollY":true,
                                    "columns": [
                                        { "data": "sid" },
                                        { "data": "site_name" },
                                        { "data": "site_location" },
                                        { "data": "manager" },
                                        { "data": "emergency_person" },
                                        { "data": "ups_factory" },
                                        { "data": "ups_service_phone" }
                                    ]
                                });
                                $('#stationinfoTpl-dialog .dataTables_scrollBody').height(100)


                                $("#stationinfoTpl-dialog").off("click").on("click",".submit-btn",function(){
                                    _this.jumpToRealTime();
                                    $dialog.dialog( "destroy" );
                                     _this.table.destroy();
                                    $(".ui-dialog,.ui-widget-overlay").remove();
                                })
                            }
                        });

                        return this;
                    }
                }
            }
        }
    return {
        init:function(){
            view = new (Backbone.View.extend(config.default.extobj))();
            return this;
        },
        show:function(id){
            console.log('station id', id);
            stationId = id;
            API.getStationInfo({id:id});
        }
    };
})