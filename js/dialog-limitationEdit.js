define(['require','api','context','common','table'],function(require,API,context,common){
    var view = null,
        catagory="1",
        siteid,
        config = {
            default:{
                extobj : {
                    data:null,
                    listPlugin:[],
                    el:'#limitationEditTpl-dialog',
                    events:{
                        "click .switch-btn":"onChangeCatagory",
                        "click .open-limitation-btn":"onOpen",
                        "click .close-limitation-btn":"onClose",
                        "click .save-limitation-btn":"onSave"
                    },
                    initialize:function(data){
                        var _this = this;
                        _this.listenTo(Backbone.Events,"alarmOptions:get",function(data){
                            _this.data = data.list;
                            _this.render();
                        });
                        _this.listenTo(Backbone.Events,"limit:open",function(data){
                            alert("开启成功");
                        });
                        _this.listenTo(Backbone.Events,"limit:close",function(data){
                            alert("关闭成功");
                        });
                        _this.listenTo(Backbone.Events,"alarmOptions:update",function(data){
                            alert("保存成功");
                        });
                    },
                    onChangeCatagory:function(e){
                        var $li = $(e.currentTarget),
                            type = $li.attr('t');
                        catagory = type;

                        this.clearTables();

                        API.getAlarmOptions({
                            sid:siteid,
                            category:catagory
                        });

                    },
                    onOpen:function(e){
                        API.openLimit({
                            id:$(e.currentTarget).attr('pid')
                        });
                    },
                    onClose:function(e){
                        API.closeLimit({
                            id:$(e.currentTarget).attr('pid')
                        });
                    },
                    onSave:function(e){
                        var $btn = $(e.currentTarget),
                            id = $btn.attr('pid'),
                            $row = $(this.el).find("."+id).parents('tr').eq(0),
                            data = common.getFormValue($row,true);

                        API.updateLimit(data);
                    },
                    refresh:function(){
                        var _this = this,
                            _param = _this.getParam();

                        if(_param){
                            _this.fetchData(_param);
                        }else{//TODO:获取参数失败

                        }
                    },
                    clearTables:function(){
                        if ( $.fn.dataTable.isDataTable( '#limitationEditTpl-dialog table')){
                            this.listPlugin.clear();
                            this.listPlugin.destroy();
                        }
                    },
                    getParam:function(){
                        var curstation = context.getCurStations(),
                            listType = context.getListType();

                        return {
                            listType:listType
                        };
                    },
                    oncancel:function(){
                        this.clearTables();
                        this.stopListening();
                        this.dialogObj && this.dialogObj.dialog( "destroy" );
                        $(".ui-dialog,.ui-widget-overlay").remove();
                    },
                    render:function(){
                        var _this = this;

                        require(["fixedColumn"],function(){
                            _this.listPlugin = $('#limitationEditTpl-dialog table').DataTable( {
                                "data": _this.data,
                                "paging": false,
                                "searching": false,
                                "info":false,
                                "scrollX":true,
                                "scrollY":570,
                                "rowId":'staffId',
                                "fixedColumns":{rightColumns:1},
                                "fnDrawCallback": function ( oSettings ) {
                                    /* Need to redo the counters if filtered or sorted */
                                    if ( oSettings.bSorted || oSettings.bFiltered ) {
                                        for ( var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ ) {
                                            $('td:eq(0)', oSettings.aoData[ oSettings.aiDisplay[i] ].nTr ).html('<label class="index">'+ (i+1)+'</label>' );
                                        }
                                    }
                                },
                                "columns": [
                                    { "data": "id",title:"序号",width:50 },
                                    { "data": "content",title:"警情描述",width:200 },
                                    //警情级别
                                    {
                                        "data": "alarm_type",
                                        title:"警情级别",
                                        width:100,
                                        render:function(value,type,itemData){
                                            var optionTpl = '<option value="<%= id %>" <%= selected %>><%= name %></option>',
                                                data = [
                                                    {id:"1",name:"红色"},
                                                    {id:"2",name:"橙色"},
                                                    {id:"3",name:"黄色"}
                                                ],
                                                optionHtml;
                                            $.each(data,function(i,d) {
                                                if(d.id == value){
                                                    d.selected="selected"
                                                }else{
                                                    d.selected=""
                                                }
                                                optionHtml += _.template(optionTpl)(d);
                                            })
                                            return '<select class="'+itemData.id+'" key="alarm_type">'+optionHtml+'</select>'
                                        }
                                    },
                                    //警情处理建议
                                    {
                                        "data": "suggest",
                                        title:"警情处理建议",
                                        width:200,
                                        render:function(data,type,itemData){
                                            return '<input type="text" class="'+itemData.id+'" key="suggest" value="'+ data +'"/><input type="hidden" key="id" value="'+itemData.id+'"><input type="hidden" key="sid" value="'+itemData.sid+'">'
                                        }
                                    },
                                    {
                                        "data": "send_email",
                                        title:"是否发邮件",
                                        width:200,
                                        render:function(data,type,itemData){
                                            return _.template('<label><input type="checkbox"" name="email" key="send_email" specify-value="1|0" <%= checked %>/>发邮件</label>')({
                                                checked:data=="1"?"checked":""
                                            })
                                        }
                                    },
                                    {
                                        "data": "send_msg",
                                        title:"是否发短信",
                                        width:200,
                                        render:function(data,type,itemData){
                                            return _.template('<label><input type="checkbox" name="email" key="send_msg" specify-value="1|0" <%= checked %>/>发短信</label>')({
                                                checked:data=="1"?"checked":""
                                            })
                                        }
                                    },
                                    // {
                                    //     "data": "operator",
                                    //     title:"判断操作",
                                    //     width:200,
                                    //     render:function(value,type,itemData){
                                    //         var optionTpl = '<option value="<%= id %>" <%= selected %>><%= name %></option>',
                                    //             data = [
                                    //                 {id:">",name:"大于等于"},
                                    //                 {id:"<",name:"小于等于"},
                                    //                 {id:"=",name:"等于"},
                                    //                 {id:"o",name:"其他"}
                                    //             ],
                                    //             optionHtml;
                                    //         $.each(data,function(i,d) {
                                    //             if(d.id == value){
                                    //                 d.selected='selected'
                                    //             }else{
                                    //                 d.selected=""
                                    //             }
                                    //             optionHtml += _.template(optionTpl)(d);
                                    //         })
                                    //         return '<select key="operator">'+optionHtml+'</select>'
                                    //     }
                                    // },
                                    //警情判断依据
                                    {
                                        "data": "type_value",
                                        title:"警情判断依据",
                                        width:200,
                                        render:function(data,type,itemData){
                                            return '<input type="text" key="type_value" value="'+ data +'"/>'
                                        }
                                    },
                                    //警情判断类型
                                    // {
                                    //     "data": "judge_type",
                                    //     title:"警情判断类型",
                                    //     width:200,
                                    //     render:function(value,type,itemData){
                                    //         var optionTpl = '<option value="<%= id %>" <%= selected %>><%= name %></option>',
                                    //             data = [
                                    //                 {id:"0",name:"绝对值"},
                                    //                 {id:"1",name:"百分比"}
                                    //             ],
                                    //             optionHtml;
                                    //         $.each(data,function(i,d) {
                                    //             if(d.id == value){
                                    //                 d.selected="selected"
                                    //             }else{
                                    //                 d.selected=""
                                    //             }
                                    //             optionHtml += _.template(optionTpl)(d);
                                    //         })
                                    //         return '<select  key="judge_type">'+optionHtml+'</select>'
                                    //     }
                                    // },
                                    { "data": "alarm_code",title:"警情代码",width:200 },
                                    {
                                        data:"id",
                                        render: function (data) {
                                            var btnTpl = '<div style="width:150px;"><span class="limitation-btns open-limitation-btn" pid="<%= id %>">开启</span>|<span pid="<%= id %>" class="limitation-btns close-limitation-btn">关闭</span>|<span pid="<%= id %>" class="limitation-btns save-limitation-btn">保存</span></div>'
                                            return _.template(btnTpl)({
                                                id:data
                                            });
                                        }
                                    }
                                ]
                            });
                            $('#stationinfoTpl-dialog .dataTables_scrollBody').height(270)
                        })
                        return this;
                    }
                }
            }
        }
    return {
        init:function(){
            return this;
        },
        show:function(id){
            var $dialog = $("#limitationEditTpl-dialog").length?$("#limitationEditTpl-dialog").replaceWith($($("#limitationEditTpl").html())):$($("#limitationEditTpl").html());
            siteid = id;
            var dialogObj = $dialog.dialog({
                modal:true,
                show:300,
                height:700,
                width:1000,
                title:'门限修改',
                close:function(evt,ui){
                    view.oncancel();
                },
                open:function(){
                    view = new (Backbone.View.extend(config.default.extobj))();
                    view.dialogObj = dialogObj;
                    API.getAlarmOptions({
                        sid:siteid,
                        category:catagory
                    });
                }
            });
        }
    };
})