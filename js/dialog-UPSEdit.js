define(['require','api','common','blocks/stationSelector'],function(require,API,common,stationSelector){
    var view = null,
        config = {
            extobj : {
                data:null,
                listPlugin:[],
                el:'#UPSEditTpl-dialog',
                events:{
                    "click .submit-btn":"onsubmit",
                    "click .cancel-btn":"oncancel"
                },
                initialize:function(data){
                    var _this = this;
                    _this.listenTo(Backbone.Events,"upsInfo:get",function(data){
                        _this.data = data;
                        _this.setValue();
                    });
                    _this.listenTo(Backbone.Events,"ups:create ups:update",function(){
                        _this.oncancel();
                        Backbone.Events.trigger("listdata:refresh", "batteryInfo");
                    });
                },
                setValue:function(data){
                    console.log('now data', data);
                    var data = data || this.data;

                    if(data){
                        // $("[key=site_name]",this.el).val(data.site_name);
                        common.setFormValue(this.el,data);
                        $("[key=site_name]",this.el).val(data.site_name);
                        $("[key=sid]",this.el).val(data.sid);
                    }
                },
                getParam:function(){
   

                    var obj = common.getFormValue(this.el,true);
                    obj.sid = $("[key=sid]").val();
                    return obj;


                },
                showErrTips:function(tips){
                    alert(tips);
                    return false;
                },
                validate:function(param){
                    // if(!param.site_name){
                    //     return this.showErrTips('站点为必填项');
                    // }
                    if(!param.sid){
                        return this.showErrTips('站点不存在');
                    }

                    var isvalidate = true;
                    var $mastFills = $(".mast-fill",this.el);
                    $mastFills.each(function(i,mf){
                        var key = $(mf).attr("for"),title;
                        if(key && (typeof param[key] == "undefined" || !param[key])){
                            title = $(mf).parent().html().replace(/<i[^>]*>.*(?=<\/i>)<\/i>/gi,'');
                            alert(title+"为必填项");
                            isvalidate = false;
                            return false;
                        }
                    })
                    if(!isvalidate){return false;}

                    return true;
                },
                onsubmit:function(){
                    var _this = this,
                        _param = _this.getParam();

                    if(_this.validate(_param)){
                        if(_param.id){
                            API.updateUpsInfo(_param);
                        }else{
                            API.createUps(_param);
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
        show:function(id,data){
            var $dialogWrap = $("#UPSEditTpl-dialog").length?$("#UPSEditTpl-dialog").replaceWith($($("#UPSEditTpl").html())):$($("#UPSEditTpl").html());
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
                height:660,
                width:1000,
                title:"UPS信息表",
                close:function(evt,ui){
                    view.oncancel();
                },
                open:function(){
                    if(!id && data){
                        $("[key=site_name]").attr('disabled','disabled');
                    }
                    $("form.jqtransform").jqTransform();
                    view = new (Backbone.View.extend(config.extobj))();
                    view.dialogObj = $(this);

                    $("form.jqtransform").html($("form.jqtransform").html().replace(/{{disabled}}/g,ifdisabled));
                    $("form.jqtransform").find("[changedisabled=disabled]").attr('disabled',true);

                    if(id){
                        API.getUpsInfo({id:id});
                    }

                    if(data){
                        console.log('init ups data', data);
                        view.setValue(data);
                    }

                    

                    stationList = stationSelector.init({
                        extOption:{
                            select:function(event, ui){
                                console.log('setup list');
                                $(this).val(ui.item.label);
                                $("[key=sid]").val(ui.item.value.substring(0,10));
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