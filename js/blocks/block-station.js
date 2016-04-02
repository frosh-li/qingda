define(['require','api','stationsinfoDialog','context','ui','table'],function(require,API,stationInfoDialog,context,ui){
    return {
        getExtObj: function (dataTableDefaultOption) {
            return {
                fetchData: function (_param) {
                    API.getStationsData(_param);
                },
                render: function () {
                    this.listPlugin = [];
                    this.listPlugin.push($('#lock table').DataTable($.extend(true, {
                        "data": this.data,
                        "columnDefs": this.getColumnDefs(),
                        "columns": [
                            {"data": "name"},
                            {"data": "id"},
                            {"data": "time"}
                        ]
                    }, dataTableDefaultOption)));
                    ui.resizeAutoListWidth();
                    this.listPlugin.push($('#auto table').DataTable($.extend(true, {
                        "data": this.data,
                        "scrollX": true,
                        "columns": [
                            {"data": "a"},
                            {"data": "v"},
                            {"data": "temperature"},
                            {"data": "temperature_max"},
                            {"data": "temperature_min"},
                            {"data": "humidity"},
                            {"data": "humidity_max"},
                            {"data": "group_num"},
                            {"data": "battery_num"},
                            {"data": "battery_state"},
                            {"data": "reserve_time"}
                        ]
                    }, dataTableDefaultOption)));
                    return this;
                }
            }
        }
    }
})