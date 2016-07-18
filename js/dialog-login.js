define(['require','api','common'],function(require,API,common){
    var view = null,
        deviceId,
        config = {
            extobj : {
                data:null,
                listPlugin:[],
                el:'#loginTpl-dialog',
                events:{
                    "click .submit-btn":"onsubmit",
                    "click .cancel-btn":"oncancel"
                },
                initialize:function(data){
                    var _this = this;
                    _this.listenTo(Backbone.Events,"login:box",function(data){
                        console.log(data);
                        localStorage.setItem('userinfo', JSON.stringify(data));
                        window.location.reload();
                    })
                    _this.listenTo(Backbone.Events,"login:box:fail",function(data){
                        alert('登录失败，请重试');
                    })
                },
                setValue:function(){
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
                    _param.refresh = 1;
                    if(_this.validate(_param)){
                        API.login(_param);
                    }
                },
                oncancel:function(){
                    this.stopListening();
                    this.dialogObj.dialog( "destroy" );
                    $(".ui-dialog,.ui-widget-overlay").remove();
                    deviceId="";
                }
            }
        };
    return {
        show:function(){
            var $dialogWrap = $("#loginTpl-dialog").length?$("#loginTpl-dialog").replaceWith($($("#loginTpl").html())):$($("#loginTpl").html());
            $dialogWrap.dialog({
                modal:true,
                show:300,
                height:250,
                width:500,
                title:"切换用户",
                close:function(evt,ui){
                    view.oncancel();
                },
                open:function(){
                    $("form.jqtransform").jqTransform();
                    view = new (Backbone.View.extend(config.extobj))();
                    view.dialogObj = $(this);
                }
            });

        },
        detroy:function(){
            view.oncancel();
        }
    };
})