define(['require','api','context'],function(require,API,context){
    var view = null,
        stationId,
        config = {
            default:{
                extobj : {
                    data:null,
                    listPlugin:[],
                    el:$('#aboutTpl-dialog'),
                    initialize:function(data){
                        var _this = this;   
                        _this.listenTo(Backbone.Events,"abouts:get",function(data){
                            _this.data = data.list[0];
                            console.log(_this.data)
                            _this.render();
                        });
                    },

                    getParam:function(){
                        var curstation = context.getCurStations(),
                            listType = context.getListType();
                        console.log('listType', listType);
                        return {
                            listType:listType
                        };
                    },
                    render:function(){
                        var _this = this,
                            $dialog = $("#aboutTpl-dialog").length?$("#aboutTpl-dialog").replaceWith($(_.template($("#aboutTpl").html())(this.data))):$(_.template($("#aboutTpl").html())(this.data));
                        console.log('start to render');
                        $dialog.dialog({
                            modal:true,
                            show:300,
                            height:400,
                            width:600,
                            title:"关于我们",
                            close:function(evt,ui){
                                $(this).dialog( "destroy" );
                            },
                            open:function(){
                                console.log('open')
                            }
                        });

                        return this;
                    }
                }
            }
        }
    return {
        init:function(){
            view = new (Backbone.View.extend(config.default.extobj))();
            return this;
        },
        show:function(){
            API.getAboutInfo();
        }
    };
})