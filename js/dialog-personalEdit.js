define(['require','api','common','blocks/stationSelector'],function(require,API,common,stationSelector){
    var view = null,
        config = {
            extobj : {
                data:null,
                listPlugin:[],
                el:'#personalEditTpl-dialog',
                events:{
                    "click .submit-btn":"onsubmit",
                    "click .cancel-btn":"oncancel"
                },
                initialize:function(data){
                    var _this = this;
                    _this.listenTo(Backbone.Events,"personalInfo:get",function(data){
                        _this.data = data;
                        _this.setValue();
                    });
                    _this.listenTo(Backbone.Events,"personal:create personal:update",function(){
                        _this.oncancel();
                        Backbone.Events.trigger("listdata:refresh", "batteryInfo");
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
                    if(!param.site_name){
                        return this.showErrTips('站点为必填项');
                    }
                    if(!param.sid){
                        return this.showErrTips('站点不存在');
                    }
                    return true;
                },
                onsubmit:function(){
                    var _this = this,
                        _param = _this.getParam();

                    if(_this.validate(_param)){
                        if(_param.id){
                            API.updatePersonalInfo(_param);
                        }else{
                            API.createPersonal(_param);
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
        },
        stationList;
    return {
        show:function(id){
            var $dialogWrap = $("#personalEditTpl-dialog").length?$("#personalEditTpl-dialog").replaceWith($($("#personalEditTpl").html())):$($("#personalEditTpl").html());

            $dialogWrap.dialog({
                modal:true,
                show:300,
                height:300,
                width:1000,
                title:id?"编辑人员信息":"添加人员",
                close:function(evt,ui){
                    view.oncancel();
                },
                open:function(){
                    $("form.jqtransform").jqTransform();
                    view = new (Backbone.View.extend(config.extobj))();
                    view.dialogObj = $(this);

                    if(id){
                        API.getPersonalInfo({id:id});
                    }

                    stationList = stationSelector.init({
                        extOption:{
                            select:function(event, ui){
                                $(this).val(ui.item.label);
                                $("[key=sid]").val(ui.item.value);
                                return false;
                            }
                        }
                    });
                }
            });
        },
        detroy:function(){
            view.oncancel();
        }
    };
})