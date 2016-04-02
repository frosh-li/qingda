define(['require','api','context','common','table'],function(require,API,context,common){
    var view = null,
        config = {
            extobj : {
                data:null,
                listPlugin:[],
                el:'#otherOptionEditTpl-dialog',
                events:{
                    "click .submit-btn":"onsubmit",
                    "click .cancel-btn":"oncancel"
                },
                initialize:function(data){
                    var _this = this;
                    _this.listenTo(Backbone.Events,"stationinfo:foredit:update",function(data){
                        _this.data = data;
                        _this.show();
                    });
                    _this.listenTo(Backbone.Events,"stationdata:create stationdata:update",function(){
                        _this.dialogObj.dialog('destroy');
                    });
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
                    return common.getFormValue(this.el);
                },
                validate:function(){
                    return true;
                },
                onsubmit:function(){
                    var _this = this,
                        _param = _this.getParam();
                    if(_this.validate(_param)){
                        if(_param.id){
                            API.updateStation(_param);
                        }else{
                            API.createStation(_param);
                        }
                    }
                },
                oncancel:function(){
                    this.dialogObj.dialog( "destroy" );
                }
            }
        }
    return {
        show:function(id){
            var $dialogWrap = $("#otherOptionEditTpl-dialog").length?$("#otherOptionEditTpl-dialog").replaceWith($($("#otherOptionEditTpl").html())):$($("#otherOptionEditTpl").html());

            $dialogWrap.dialog({
                modal:true,
                show:300,
                height:550,
                width:900,
                title:id?"编辑其他参数":"添加其他参数",
                close:function(evt,ui){
                    $(this).dialog( "destroy" );
                },
                open:function(){
                    $("form.jqtransform").jqTransform();
                    view = new (Backbone.View.extend(config.extobj))();
                    view.dialogObj = $(this);

                    if(id){
                        API.getStationEditInfo(id);
                    }
                }
            });

        }
    };
})