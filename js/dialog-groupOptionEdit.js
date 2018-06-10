define(['require','api','common','blocks/stationSelector'],function(require,API,common,stationSelector){
    var view = null,
        config = {
            extobj : {
                data:null,
                listPlugin:[],
                el:'#groupOptionEditTpl-dialog',
                events:{
                    "click .submit-btn":"onsubmit",
                    "click .cancel-btn":"oncancel"
                },
                initialize:function(data){
                    var _this = this;
                    _this.listenTo(Backbone.Events,"groupoption:get",function(data){
                        _this.data = data;
                        _this.setValue();
                    });

                    _this.listenTo(Backbone.Events,"groupoption:update",function(){
                        _this.showErrTips('修改完成，请等待5到10秒后刷新查看');
                        _this.oncancel();
                        API.getGroupOptionData();
                        Backbone.Events.trigger("listdata:refresh", "groupOption");

                    });
                     _this.listenTo(Backbone.Events,"groupoption:update:fail",function(msg){
                        _this.showErrTips(msg);
                    });


                },
                setValue:function(){
                    if(this.data){
                        common.setFormValue(this.el,this.data);
                    }
                },
                getParam:function(){
                    return common.getFormValue(this.el,true);
                },
                showErrTips:function(tips){
                    alert(tips);
                    return false;
                },
                validate:function(param){
                    /* if(!param.site_name){
                     return this.showErrTips('站点为必填项');
                     }
                     if(!param.sid){
                     return this.showErrTips('站点不存在');
                     } */
                    return true;
                },
                onsubmit:function(){
                    var _this = this,
                        _param = _this.getParam();

                    if(_this.validate(_param)){
                        if(_param.sn_key){
                            API.updateGroupOption(_param);
							API.syncHard(_param,"GroupPar");
                        }else{
                            _this.showErrTips('no sn_key');
                            //API.createBMS(_param);
                        }
                    }
                },
                oncancel:function(){
                    this.stopListening();
                    this.dialogObj.dialog( "destroy" );
                    $(".ui-dialog,.ui-widget-overlay").remove();
                    stationList.autocomplete('destroy');
                }
            }
        };
    return {
        show:function(id){
            var $dialogWrap = $("#groupOptionEditTpl-dialog").length?$("#groupOptionEditTpl-dialog").replaceWith($($("#groupOptionEditTpl").html())):$($("#groupOptionEditTpl").html());

            $dialogWrap.dialog({
                modal:true,
                show:300,
                height:660,
                width:1000,
                title:"编辑组参数",
                close:function(evt,ui){
                    view.oncancel();
                },
                open:function(){
                    $("form.jqtransform").jqTransform();
                    view = new (Backbone.View.extend(config.extobj))();
                    view.dialogObj = $(this);

                    if(id){
                        API.getGroupOption({sn_key:id});
                    }
                }
            });
        },
        detroy:function(){
            view.oncancel();
        }
    };
})
