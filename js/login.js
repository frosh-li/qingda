define(["require","backbone","api","router","common"],function(require,Backbone,API,router,common){
    var view,
        View =  Backbone.View.extend({
            el:"#login",
            options:{},
            events:{
                "click #submitBtn":"onSbumit"
            },
            initialize: function(options) {
                var _this = this;
                $("input[key=password]").val("");
                $("input[key=username]").val(localStorage.getItem("username") || "");
                _this.listenTo(Backbone.Events,"login",function(data){
                    console.log(data);
                    localStorage.setItem('userinfo', JSON.stringify(data));
                    localStorage.setItem("collecting",'true');
                    localStorage.setItem('username',data.username);
                    router.to("manage/station");
                })
                _this.listenTo(Backbone.Events,"login:fail",function(data){
                    localStorage.clear();
                    alert('登录失败，请重试');
                })
            },
            onSbumit:function(){
                var param = common.getFormValue(this.el);
                API.login(param);
            }
        });


    return {
        init:function(pageType){
            view = new View();
            return this;
        }
    };
})