define(['require','api','stationsinfoDialog','context','ui','table'],function(require,API,stationInfoDialog,context,ui){
    return {
        getExtObj: function (dataTableDefaultOption) {
            return {
                fetchData: function (_param) {
                    API.getCautionsData(_param);
                },
                render: function () {
                    this.listPlugin = [];
                    this.listPlugin.push($('#caution table').DataTable($.extend(true, {
                        "data": this.data,
                        "columns": [
                            {"data": "name"},
                            {"data": "id"},
                            {"data": "group_id"},
                            {"data": "time"},
                            {"data": "a"},
                            {"data": "v"},
                            {"data": "hydrogen"},
                            {"data": "battery_num"},
                            {"data": "battery_state"},
                            {"data": "discharge_a_max"},
                            {"data": "charge_a_max"}
                        ]
                    }, dataTableDefaultOption)));
                    return this;
                }
            }
        }
    }
})