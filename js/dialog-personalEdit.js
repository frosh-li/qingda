define(['require','api','common','blocks/areaSelector'],function(require,API,common,areaSelector){
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

                    //_this.listenTo(Backbone.Events,"stationinfo:foredit:update",function(data){
                    this.level = this.level||areaSelector.init();
                    console.log(this.level);
                    //});

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
                    return true;
                },
                onsubmit:function(){
                    var _this = this,
                        _param = _this.getParam();
                    _param.area = this.level.getValue();
                    _param.role = $("[key=role]",this.el).val();
                    console.log(_param);
                    console.log(this.level,'level');
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
                    this.level.destroy();
                    this.level = null;
                }
            }
        };
    return {
        show:function(id){
            var $dialogWrap = $("#personalEditTpl-dialog").length?$("#personalEditTpl-dialog").replaceWith($($("#personalEditTpl").html())):$($("#personalEditTpl").html());
            try{
                var roleid = JSON.parse(localStorage.getItem('userinfo')).role;
            }catch(e){
                var roleid = 3;
                console.log(e);
            }

            $dialogWrap.dialog({
                modal:true,
                show:300,
                height:500,
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
                        API.getPersonalInfo({id:id}, function(){
                            console.log('get data');
                        });
                    }
                    setTimeout(function(){
                        // if(roleid == 1){
                        //     $('.rolelist').append('<option value=1>超级管理员</option>');
                        //     $('.rolelist').append('<option value=2>管理员</option>');
                        //     $('.rolelist').append('<option value=3>观察员</option>');
                        // }else if(roleid == 2){
                        //     $('.rolelist').append('<option value=1>超级管理员</option>');
                        //     $('.rolelist').append('<option value=2>管理员</option>');
                        //     $('.rolelist').append('<option value=3>观察员</option>');
                        // }
                    },1000)
                    

                }
            });
        },
        detroy:function(){
            view.oncancel();
        }
    };
})