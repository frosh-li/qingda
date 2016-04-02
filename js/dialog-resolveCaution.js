define(['require','api','common','blocks/stationSelector'],function(require,API,common,stationSelector){
    var view = null,
        config = {
            extobj : {
                data:null,
                listPlugin:[],
                el:'#ResolveCautionTpl-dialog',
                events:{
                    "click .submit-btn":"onsubmit",
                    "click .cancel-btn":"oncancel"
                },
                initialize:function(data){
                    var _this = this;
                    _this.listenTo(Backbone.Events,"bmsInfo:get",function(data){
                        _this.data = data;
                        _this.setValue();
                    });
                    _this.listenTo(Backbone.Events,"bms:create bms:update",function(){
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
                    var isvalidate = true;
                    $mastFills = $(".mast-fill",this.el);
                    $mastFills.each(function(i,mf){
                        var key = $(mf).attr("for"),title;
                        if(typeof param[key] == "undefined" || !param[key]){
                            title = $(mf).parent().html().replace(/<i[^>]*>.*(?=<\/i>)<\/i>/gi,'');
                            alert(title+"为必填项");
                            isvalidate = false;
                            return false;
                        }
                    })

                    return isvalidate;
                },
                onsubmit:function(){
                    var _this = this,
                        _param = _this.getParam();

                    if(_this.validate(_param)){
                        API.resolveCaution({
                            "alarm_sn":""
                        });
                    }
                },
                oncancel:function(){
                    this.stopListening();
                    this.dialogObj.dialog( "destroy" );
                    $(".ui-dialog,.ui-widget-overlay").remove();
                }
            }
        },
        stationList;
    return {
        show:function(data){
            var $dialogWrap = $("#ResolveCautionTpl-dialog").length?$("#ResolveCautionTpl-dialog").replaceWith($($("#ResolveCautionTpl").html())):$($("#ResolveCautionTpl").html());

            $dialogWrap.dialog({
                modal:true,
                show:300,
                height:400,
                width:600,
                title:"警情处理",
                close:function(evt,ui){
                    view.oncancel();
                },
                open:function(){
                    $("form.jqtransform").jqTransform();
                    view = new (Backbone.View.extend(config.extobj))();
                    view.dialogObj = $(this);

                    if(data){
                        $("[key=alarm_sn]",$dialogWrap).val(data.id);
                        $("#suggestion",$dialogWrap).val(data.alarm_suggestion);
                    }
                }
            });
        },
        detroy:function(){
            view.oncancel();
        }
    };
})