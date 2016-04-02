define(['require','api','stationsinfoDialog','context','ui','table','blocks/block-staiton'],function(require,API,stationInfoDialog,context,ui,list){
    return {
        getExtObj: function (dataTableDefaultOption) {
            return list.getExtObj(dataTableDefaultOption);
        }
    }
})