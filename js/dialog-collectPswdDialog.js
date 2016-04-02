define(['require','api','common'],function(require,API,common){
    var view = null,
        deviceId,
        config = {
            extobj : {
                data:null,
                listPlugin:[],
                el:'#collectPswdDialogTpl-dialog',
                events:{
                    "click .submit-btn":"onsubmit",
                    "click .cancel-btn":"oncancel"
                },
                initialize:function(data){
                    var _this = this;
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
                    if(!param.pass){
                        return this.showErrTips('请输入密码');
                    }
                    return true;
                },
                onsubmit:function(){
                    var _this = this,
                        _param = _this.getParam();

                    if(_this.validate(_param)){
                        Backbone.Events.trigger("forcecollect:start", _param);
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
        show:function(id){
            var $dialogWrap = $("#collectPswdDialogTpl-dialog").length?$("#collectPswdDialogTpl-dialog").replaceWith($($("#collectPswdDialogTpl").html())):$($("#collectPswdDialogTpl").html());
            deviceId=id;
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