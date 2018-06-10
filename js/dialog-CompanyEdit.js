define(['require','api','common','blocks/areaSelector'],function(require,API,common,areaSelector){
    var view = null,
        config = {
            extobj : {
                data:null,
                listPlugin:[],
                el:'#CompanyEditTpl-dialog',
                events:{
                    "click .submit-btn":"onsubmit",
                    "click .cancel-btn":"oncancel"
                },
                initialize:function(data){
                    var _this = this;
                    _this.listenTo(Backbone.Events,"companyInfo:get",function(data){
                        _this.data = data;
                        //选择器编辑的情况
                        level = areaSelector.init({value:data.area});
                        _this.setValue();
                    });
                    _this.listenTo(Backbone.Events,"company:create",function(){
                        alert("添加成功");
                        _this.oncancel();
                        Backbone.Events.trigger("listdata:refresh", "batteryInfo");
                    });

                    _this.listenTo(Backbone.Events,"company:update",function(){
                        window.location.reload();
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
                    _param.area = level.getValue(true);
                    // alert(_param.area);
                    // return;

                    if(_this.validate(_param)){
                        if(_param.id){
                            API.updateCompanyInfo(_param);
                        }else{
                            API.createCompany(_param);
                        }
                    }
                },
                oncancel:function(){
                    this.stopListening();
                    this.dialogObj.dialog( "destroy" );
                    level.destroy();
                    level = null;
                    $(".ui-dialog,.ui-widget-overlay").remove();
                }
            }
        },
        level;
    return {
        show:function(id){
            var $dialogWrap = $("#CompanyEditTpl-dialog").length?$("#CompanyEditTpl-dialog").replaceWith($($("#CompanyEditTpl").html())):$($("#CompanyEditTpl").html());

            $dialogWrap.dialog({
                modal:true,
                show:300,
                height:660,
                width:1000,
                title:id?"编辑主管单位信息":"添加主管单位信息",
                close:function(evt,ui){
                    view.oncancel();
                },
                open:function(){
                    $("form.jqtransform").jqTransform();
                    view = new (Backbone.View.extend(config.extobj))();
                    view.dialogObj = $(this);

                    if(id){
                        API.getCompanyInfo({id:id});
                    }else{
                        //区域选择器
                        level = areaSelector.init();
                    }

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