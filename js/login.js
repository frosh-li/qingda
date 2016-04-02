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
                _this.listenTo(Backbone.Events,"login",function(data){
                    router.to("manage/station");
                })
                _this.listenTo(Backbone.Events,"login:fail",function(data){

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