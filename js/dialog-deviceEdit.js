define(['require','api','common','blocks/stationSelector'],function(require,API,common,stationSlector){
    var view = null,
        deviceId,
        config = {
            extobj : {
                data:null,
                listPlugin:[],
                el:'#deviceEditTpl-dialog',
                events:{
                    "click .submit-btn":"onsubmit",
                    "click .cancel-btn":"oncancel"
                },
                initialize:function(data){
                    var _this = this;
                    _this.listenTo(Backbone.Events,"stationdevice:get",function(data){
                        _this.data = data;
                        _this.setValue();
                    });
                    _this.listenTo(Backbone.Events,"stationdevice:create stationdevice:update",function(){
                        _this.oncancel();
                        Backbone.Events.trigger("listdata:refresh", "device");
                    });
                },
                setValue:function(){
                    if(this.data){
                        common.setFormValue(this.el, $.extend({id:deviceId},this.data));
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
                            API.updateStationdevice(_param);
                        }else{
                            API.createStationdevice(_param);
                        }
                    }
                },
                oncancel:function(){
                    this.stopListening();
                    this.dialogObj.dialog( "destroy" );
                    $(".ui-dialog,.ui-widget-overlay").remove();
                    stationList.autocomplete('destroy');

                    deviceId="";
                }
            }
        },
        stationList;
    return {
        show:function(id){
            var $dialogWrap = $("#deviceEditTpl-dialog").length?$("#deviceEditTpl-dialog").replaceWith($($("#deviceEditTpl").html())):$($("#deviceEditTpl").html());
            deviceId=id;
            $dialogWrap.dialog({
                modal:true,
                show:300,
                height:400,
                width:1000,
                title:"编辑",
                close:function(evt,ui){
                    view.oncancel();
                },
                open:function(){
                    $("form.jqtransform").jqTransform();
                    view = new (Backbone.View.extend(config.extobj))();
                    view.dialogObj = $(this);

                    if(id){
                        API.getStationdevice({id:id});
                    }

                    stationList = stationSlector.init({
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