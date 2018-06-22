define(['require','api','common','blocks/levelSlector'],function(require,API,common,levelSelector){
    var view = null,
        config = {
            extobj : {
                data:null,
                listPlugin:[],
                el:'#stationEditTpl-dialog',
                events:{
                    "click .submit-btn":"onsubmit",
                    "click .next-btn":"onnext",
                    "click .cancel-btn":"oncancel"
                },
                initialize:function(data){
                    var _this = this;

                    _this.listenTo(Backbone.Events,"stationinfo:foredit:update",function(data){
                        _this.data = data;
                        _this.data.serial_number = _this.data.serial_number.substring(0,10);
                        level = level||levelSelector.init({value:data.aid});
                        _this.setValue();
                    });
                    _this.listenTo(Backbone.Events,"stationdata:create stationdata:update",function(data){
                        common.loadTips.close();
                        _this.oncancel();
                        window.location.reload();
                        Backbone.Events.trigger("listdata:refresh", "station");
                    });
                    _this.listenTo(Backbone.Events,"stationdata:create:fail stationdata:update:fail",function(data){
                        console.log('fail data', data)
                        common.loadTips.close();
                        alert(data.response.msg);
                    });
                    _this.listenTo(Backbone.Events,"station:create:next",function(data){
                        common.loadTips.close();
                        _this.oncancel();
                        Backbone.Events.trigger("listdata:refresh", "station");
                        data.serial_number = $("[key=serial_number]",this.el).val();
                        Backbone.Events.trigger("station:next", data);
                    });
                    _this.listenTo(Backbone.Events,"station:create:next:fail",function(data){
                        common.loadTips.close();
                        alert(data.response.msg);
                    });
                },
                setValue:function(){
                    if(this.data){
                        common.setFormValue(this.el,this.data);
                    }
                },
                getParam:function(){
                    var obj = common.getFormValue(this.el,true);
                    obj.serial_number = $("[key=serial_number]",this.el).val()+"0000";
                    console.log('getParam', obj);
                    return obj;
                },
                showErrTips:function(tips){
                    alert(tips);
                    return false;
                },
                validate:function(param){
                    if(!param.area){
                        return this.showErrTips('隶属区域为必填项');
                    }
                    if(!param.sid){
                        return this.showErrTips('站号为必填项');
                    }
                    if (!param.CurSensor){
                        return this.showErrTips('电流传感器安装状态为必填项');
                    }
                    if (!param.StationFullChineseName){
                        return this.showErrTips('站点全称为必填项');
                    }
                    if (!param.groups){
                        return this.showErrTips('电池组数为必填项');
                    }
                    if (!param.batteries){
                        return this.showErrTips('每组电池数为必填项');
                    }

                    var isvalidate = true;
                    var $mastFills = $(".mast-fill",this.el);
                    $mastFills.each(function(i,mf){
                        var key = $(mf).attr("for"),title;
                        if(key && (typeof param[key] == "undefined" || !param[key])){
                            title = $(mf).parent().html().replace(/<i[^>]*>.*(?=<\/i>)<\/i>/gi,'');
                            title = title.replace(': ','');
                            alert(title+"为必填项");
                            isvalidate = false;
                            return false;
                        }
                    })
                    if(!isvalidate){return false;}

                    if(!/^\d{14}$/.test(param.serial_number)){
                        return this.showErrTips('物理地址必须为10位数字');
                    }


                    return true;
                },
                onsubmit:function(){
                    var _this = this,
                        _param = _this.getParam();

                    _param.area = level.getValue();

                    if(_this.validate(_param)){
                        common.loadTips.show("正在保存，请稍后...");
                        if(_param.id){
                            API.syncHard({
                                sn_key:_param.serial_number,
                                CurSensor:_param.CurSensor || 101,
                                sid:_param.sid,
                                Groups:_param.groups,
                                GroBats:_param.batteries
                            },"StationPar");

                            API.updateStation(_param);
                        }else{
                            API.syncHard({
                                sn_key:_param.serial_number,
                                CurSensor:_param.CurSensor || 101,
                                sid:_param.sid,
                                Groups:_param.groups,
                                GroBats:_param.batteries
                            },"StationPar");

                            API.createStation(_param);
                            
                        }
                    }
                },
                onnext:function(){
                    var _this = this,
                        _param = _this.getParam();

                    _param.area = level.getValue();

                    if(_this.validate(_param)){
                        API.createStation(_param,"station:create:next");
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
        show:function(id, bringdata){
            var $dialogWrap = $("#stationEditTpl-dialog").length?$("#stationEditTpl-dialog").replaceWith($($("#stationEditTpl").html())):$($("#stationEditTpl").html());
            var roleid = JSON.parse(localStorage.getItem('userinfo')).role;
            var ifdisabled = "";
            if(roleid != 1){
                ifdisabled = "disabled";
            }else{
                ifdisabled = "";
            }

            // $dialogWrap = $($dialogWrap);
            $dialogWrap.dialog({
                modal:true,
                show:300,
                height:820,
                width:1000,
                title:id?"编辑站点":"添加站点",
                close:function(evt,ui){
                    view.oncancel();
                },
                open:function(){
                    
                    view = new (Backbone.View.extend(config.extobj))();

                    view.dialogObj = $(this);
                    $("form.jqtransform").html($("form.jqtransform").html().replace(/{{disabled}}/g,ifdisabled));
                    $("form.jqtransform").find("[changedisabled=disabled]").attr('disabled',true);
                    if(id){
                        $(".submit-btn",view.el).show();
                        API.getStationEditInfo({id:id});
                    }else{
                        $(".next-btn",view.el).show();
                        //层级选择器
                        level = levelSelector.init();
                    }
                    if(bringdata){
                        console.log('bringdata',bringdata)
                        $("[key=serial_number]").val(bringdata.sn_key.substring(0,10));
                        $("[key=sid]").val(bringdata.sid);
                        $("[key=groups]").val(bringdata.Groups);
                        $("[key=batteries]").val(bringdata.GroBats);
                        $("[key=CurSensor]").val(bringdata.CurSensor);
                    }
                    //日期选择器
                    $( "form.jqtransform [key=bms_install_date]" ).datepicker({
                        dateFormat: "yy-mm-dd"
                    });

                    $("form.jqtransform").jqTransform();
                }
            });

        },
        detroy:function(){
            view.oncancel();
        }
    };
})