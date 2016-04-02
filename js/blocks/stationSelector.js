define(['require','api','backbone'],function(require,API,Backbone){
    var zTree,
        setting = {
            source: function( request, response ) {
                API.getStationSlectorList({q:request.term});
                Backbone.Events.on("stationSelectorList:get",function(ret){
                    if(ret && ret.list){
                        var stations=[];
                        $.each(ret.list,function(id,name){
                            stations.push({
                                label:name,
                                value:id
                            })
                        })
                    }
                    response(stations);
                });
            },
            minLength:2
        },
        _autocomplete;

    return {
        init:function(option){
            option = option||{};
            return _autocomplete = $(option.ipt_id||'#stationSelector').autocomplete($.extend(setting,option.extOption||{}));
        },
        destroy:function(){
            _autocomplete.autocomplete('destroy');
        }
    }
})