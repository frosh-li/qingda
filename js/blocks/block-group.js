define(['require','api','stationsinfoDialog','context','ui','table'],function(require,API,stationInfoDialog,context,ui){
    return {
        getExtObj: function (dataTableDefaultOption) {
            return {
                fetchData: function (_param) {
                    API.getGroupsData(_param);
                },
                render: function () {
                    this.listPlugin = [];
                    this.listPlugin.push($('#lock table').DataTable($.extend(true, {
                        "data": this.data,
                        "columnDefs": this.getColumnDefs(),
                        "columns": [
                            {"data": "name"},
                            {"data": "id"},
                            {"data": "group_id"},
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
                            {"data": "hydrogen"},
                            {"data": "battery_num"},
                            {"data": "battery_state"},
                            {"data": "discharge_a_max"},
                            {"data": "charge_a_max"},
                            {"data": "group_hydrogen_max"}
                        ]
                    }, dataTableDefaultOption)));
                    return this;
                }
            }
        }
    }
})