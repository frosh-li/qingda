define(['require','api','common','blocks/stationSelector'],function(require,API,common,stationSelector){
    var view = null,
        config = {
            extobj : {
                data:null,
                listPlugin:[],
                el:'#baterryOptionEditTpl-dialog',
                events:{
                    "click .submit-btn":"onsubmit",
                    "click .cancel-btn":"oncancel"
                },
                initialize:function(data){
                    var _this = this;
                    _this.listenTo(Backbone.Events,"batteryoption:get",function(data){
                        _this.data = data;
                        _this.setValue();
                    });
                    _this.listenTo(Backbone.Events,"batteryoption:update",function(){
                        _this.oncancel();
                        Backbone.Events.trigger("listdata:refresh", "batteryOption");
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
                        if(_param.sid){
                            API.updateBatteryOption(_param);
                        }else{
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
        },
        stationList;
    return {
        show:function(id){
            var $dialogWrap = $("#baterryOptionEditTpl-dialog").length?$("#baterryOptionEditTpl-dialog").replaceWith($($("#baterryOptionEditTpl").html())):$($("#baterryOptionEditTpl").html());

            $dialogWrap.dialog({
                modal:true,
                show:300,
                height:660,
                width:1000,
                title:"编辑参数",
                close:function(evt,ui){
                    view.oncancel();
                },
                open:function(){
                    $("form.jqtransform").jqTransform();
                    view = new (Backbone.View.extend(config.extobj))();
                    view.dialogObj = $(this);

                    if(id){
                        API.getBatteryOption({bid:id});
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

                    //日期选择器
                    $( "form.jqtransform [ipttype=date]" ).datepicker({
                        dateFormat: "yy-mm-dd"
                    });
                }
            });
        },
        detroy:function(){
            view.oncancel();
        }
    };
})