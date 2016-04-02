define(['require','api','common','blocks/levelSlector'],function(require,API,common,levelSelector){
    var view = null,
        config = {
            extobj : {
                data:null,
                listPlugin:[],
                el:'#messageEditTpl-dialog',
                events:{
                    "click .submit-btn":"onsubmit",
                    "click .cancel-btn":"oncancel"
                },
                initialize:function(data){
                    var _this = this;
                    _this.listenTo(Backbone.Events,"message:update",function(data){
                        _this.data = data;
                        level = level||levelSelector.init({value:data.aid});
                        _this.setValue();
                    });
                    _this.listenTo(Backbone.Events,"message:create message:update",function(){
                        _this.oncancel();
                        Backbone.Events.trigger("listdata:refresh", "message");
                    });
                },
                setValue:function(){
                    if(this.data){
                        common.setFormValue(this.el,this.data);
                    }
                },
                getParam:function(){
                    var param = common.getFormValue(this.el,true);

                    param.phone_status = "0";
                    if($("[key=phone_status]",this.el).siblings(".jqTransformChecked").length){
                        param.phone_status = "1";
                    }
                    param.email_status = "0";
                    if($("[key=email_status]",this.el).siblings(".jqTransformChecked").length){
                        param.email_status = "1";
                    }

                    return param;
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
                        if(key && (typeof param[key] == "undefined" || !param[key])){
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

                    _param.area = level.getValue();

                    if(_this.validate(_param)){
                        if(_param.id){
                            API.updateMessage(_param);
                        }else{
                            API.createMessage(_param);
                        }
                    }
                },
                oncancel:function(){
                    this.stopListening();
                    level.destroy();
                    level = null;
                    this.dialogObj.dialog( "destroy" );
                    $(".ui-dialog,.ui-widget-overlay").remove();
                }
            }
        },
        level;
    return {
        show:function(id){
            var $dialogWrap = $("#messageEditTpl-dialog").length?$("#messageEditTpl-dialog").replaceWith($($("#messageEditTpl").html())):$($("#messageEditTpl").html());

            $dialogWrap.dialog({
                modal:true,
                show:300,
                height:420,
                width:600,
                title:id?"编辑短信/邮箱":"添加短信/邮箱",
                close:function(evt,ui){
                    view.oncancel();
                },
                open:function(){
                    $("form.jqtransform").jqTransform();
                    view = new (Backbone.View.extend(config.extobj))();
                    view.dialogObj = $(this);

                    if(id){
                        API.getMessageInfo({id:id});
                    }else{
                        //层级选择器
                        level = levelSelector.init();
                    }
                    //日期选择器
                    $( "form.jqtransform [key=bms_install_date]" ).datepicker({
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