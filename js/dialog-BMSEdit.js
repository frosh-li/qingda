define(['require','api','common','blocks/stationSelector'],function(require,API,common,stationSelector){
    var view = null,
        config = {
            extobj : {
                data:null,
                listPlugin:[],
                el:'#BMSEditTpl-dialog',
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
                        window.location.reload();
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
                        if(_param.id){
                            API.updateBMSInfo(_param);
                        }else{
                            API.createBMS(_param);
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
            var $dialogWrap = $("#BMSEditTpl-dialog").length?$("#BMSEditTpl-dialog").replaceWith($($("#BMSEditTpl").html())):$($("#BMSEditTpl").html());
            var roleid = JSON.parse(localStorage.getItem('userinfo')).role;
            var ifdisabled = "";
            if(roleid != 1){
                ifdisabled = "disabled";
            }else{
                ifdisabled = "";
            }
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
                    console.log('dianchi');
                    $("form.jqtransform").jqTransform();
                    view = new (Backbone.View.extend(config.extobj))();
                    view.dialogObj = $(this);
                    $("form.jqtransform").html($("form.jqtransform").html().replace(/{{disabled}}/g,ifdisabled));
                    $("form.jqtransform").find("[changedisabled=disabled]").attr('disabled',true);
                    if(id){
                        API.getBMSInfo({id:id});
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