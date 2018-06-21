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
                    _this.level = areaSelector.init();
                    _this.listenTo(Backbone.Events,"personalInfo:get",function(data){
                        _this.data = data;
                        this.level = areaSelector.init({value:data.area});
                        _this.setValue();
                    });
                    _this.listenTo(Backbone.Events,"personal:create personal:update",function(){
                        // return;
                        window.location.reload();
                        _this.oncancel();
                        Backbone.Events.trigger("listdata:refresh", "batteryInfo");
                    });
                    _this.listenTo(Backbone.Events,"personal:create:fail",function(data){
                        console.log(data);
                        if (data.response.code == -1){
                            alert('已经存在相同的用户！');
                        }else if(data.response.code == -2){
                            alert(data.response.msg);
                        }
                    });

                    _this.listenTo(Backbone.Events,"personal:update:fail",function(data){
                        alert(data.response.msg);
                        return;
                        //Backbone.Events.trigger("listdata:refresh", "batteryInfo");
                    });
                    $('[key=role]').prev().find('li a').click(function(){
                        // console.log($(this).attr('index'));
                        var user = $(this).html();
                        if (user == '观察员'){
                            $('[key=canedit]').prev().prev().find('span').html('否');
                            $('[key=canedit]').prev().find('li a').removeClass().eq(1).addClass('selected');
                            $('[key=canedit] option[index=1]').attr("selected",true);
                        }else{
                            $('[key=canedit]').prev().prev().find('span').html('是');
                            $('[key=canedit]').prev().find('li a').removeClass().eq(0).addClass('selected');
                            $('[key=canedit] option[index=0]').attr("selected",true);
                        }
                        // console.log(user);
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
                    _param.area = this.level.getValue(true);
                    _param.role = $("[key=role]",this.el).val();
                    _param.canedit = $("[key=canedit]",this.el).val();
                    console.log(_param);
                    console.log(this.level,'level');
                    var refresh = $("[key=refresh]",this.el).val();
                    if (refresh < 15){
                        alert('刷新频率不得小于15秒');
                        return;
                    }
                    if (refresh > 60){
                        alert('刷新频率不得大于60秒');
                        return;
                    }
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
            var _this = this;
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
                    if (roleid == 2){
                        $('[key=role]').prev().find('li').eq(0).remove();
                        $('[key=role] option').eq(0).remove();
                    }

                    $("form.jqtransform").jqTransform();

                    view = new (Backbone.View.extend(config.extobj))();
                    view.dialogObj = $(this);

                    if(id){
                        API.getPersonalInfo({id:id}, function(){
                            console.log('get data');
                        });
                    }else{
                        _this.level = _this.level || areaSelector.init();
                        console.log(_this.level);
                        $('[key=refresh').val(20);
                    }

                }
            });
        },
        detroy:function(){
            view.oncancel();
        }
    };
})