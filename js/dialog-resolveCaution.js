define(['require','api','common','blocks/stationSelector'],function(require,API,common,stationSelector){
    var view = null,
        config = {
            extobj : {
                data:null,
                listPlugin:[],
                el:'#ResolveCautionTpl-dialog',
                events:{
                    "click .submit-btn.sdone":"onsubmit",
                    "click .submit-btn.ignore":"onignore",
                    "click .cancel-btn":"oncancel"
                },
                initialize:function(data){
                    var _this = this;
                    // _this.listenTo(Backbone.Events,"bmsInfo:get",function(data){
                    //     _this.data = data;
                    //     _this.setValue();
                    // });
                    _this.listenTo(Backbone.Events,"caution:resolved",function(){
                        _this.showErrTips('处理完成');
                        Backbone.Events.trigger("listdata:refresh", "caution");
                        _this.oncancel();
                        // Backbone.Events.trigger("listdata:refresh", "batteryInfo");
                    });
                    _this.listenTo(Backbone.Events,"caution:resolved:fail",function(data){
                        _this.showErrTips(data.response.message);
                        // Backbone.Events.trigger("listdata:refresh", "caution");
                        // _this.oncancel();
                        // Backbone.Events.trigger("listdata:refresh", "batteryInfo");
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
                            "id":_param.id,
                            "markup":_param.markup,
                            "contact":_param.contact,
                            "stauts":1,
                            "sn_key":_param.sn_key,
                            "code":_param.code,
                            "type":_param.type
                        });
                    }
                },
                onignore:function(){
                    var _this = this,
                        _param = _this.getParam();

                    // if(_this.validate(_param)){
                        API.resolveCaution({
                            "id":_param.id,
                            "markup":_param.markup,
                            "contact":"",
                            "status":2,
                            "sn_key":_param.sn_key,
                            "code":_param.code,
                            "type":_param.type,
                        });
                    // }
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
                title:data.realtime === true ? "忽略警情":"警情处理",
                close:function(evt,ui){
                    view.oncancel();
                },
                open:function(){
                    $("form.jqtransform").jqTransform();
                    view = new (Backbone.View.extend(config.extobj))();
                    view.dialogObj = $(this);
                    console.log(data);
                    if(data){
                        $("[key=id]",$dialogWrap).val(data.id);
                        $("[key=code]",$dialogWrap).val(data.code);
                        $("[key=sn_key]",$dialogWrap).val(data.sn_key);
                        $("[key=type]",$dialogWrap).val(data.type);
                        $("#suggestion",$dialogWrap).html(data.suggestion);
                        $("#desc",$dialogWrap).html(data.desc);
                    }
                    if(data.realtime === true){
                      $(".sdone", $dialogWrap).hide()
                    }else{
                      $(".ignoreTips", $dialogWrap).hide();
                      $(".ignore", $dialogWrap).hide()
                    }
                }
            });
        },
        detroy:function(){
            view.oncancel();
        }
    };
})
