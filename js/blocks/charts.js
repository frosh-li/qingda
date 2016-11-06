define(['require','api','ui','backbone'],function(require,API,ui,Backbone){
/*define(['require','api','ui','backbone','echarts'],function(require,API,ui,Backbone,echart){*/
    var navView,
        overFlag = false,
        echart,
        sys,listType,sub,curids="",curEvtType,
        charType="bar",
        ALARM_COLOR = {
            "0":"green",
            "1":"#f76363",
            "2":"#ff975e",
            "3":"#ffee2c"
        },
        ALARM_SYMBOL = {
            '0':'image://images/chart-normal.png',
            '1':'image://images/chart-caution.png',
            '2':'image://images/chart-alert.png',
            '3':'image://images/chart-notice.png'
        };

    var chart_extobj = {
        data:null,
        navPlugin:null,
        el:$(".chart-wrap"),
        chart:null,
        chartOption:{},
        chartType:'line',
        origindata:null,
        chartName:"",
        initialize:function(data){
            var _this = this;
            _this.listenTo(Backbone.Events,"listdata:update stationdata:get",function(data){
                _this.updateChart(data);
            });
        },
        clear:function(){

        },
        getLevel:function(value,range){

        },
        updateChart:function(data){
            var _this = this;
            _this.origindata = data||_this.origindata;

                var col = _this.getFieldValue();
                // console.log('current filed', col);
                var ctype = "station";
                //window.location.hash.indexOf("station") > -1 ? "station":(window.location.hash.indexOf("station")>-1)
                var hash = window.location.hash;

                var values = [];
                var xAixs = [];
                if(!_this.origindata.list){
                    _this.origindata.list = [];
                }
                for(var i = 0 ; i < _this.origindata.list.length; i++){
                    var cdata =  _this.origindata.list[i];
                        if(hash.indexOf('station') > -1){
                            xAixs.push(cdata.site_name+"-"+cdata.sid);
                        }else if(hash.indexOf("group") > -1){
                            xAixs.push(cdata.site_name+"-"+cdata.sid+"\n"+"组"+cdata.gid);
                        }else if(hash.indexOf("battery") > -1){
                            xAixs.push(cdata.site_name+"-"+cdata.sid+"\n"+"组"+cdata.gid+"-"+cdata.bid);
                        }
                        var status = cdata[col.substring(0,3)+"Col"] || 0;
                        if(col == "Dev_R" || col=="Dev_T" || col=="Dev_U"){
                            status = cdata[col.replace("_","")+"Col"];
                        }

                        values.push({
                            value:cdata[col],
                            symbol:ALARM_SYMBOL[status],
                            symbolSize:14,
                            lineStyle:{
                                normal:{
                                    color: 'yellow',
                                    width:2
                                }
                            },
                            smooth:true,
                            itemStyle:{
                                // color:ALARM_COLOR[status],
                                normal:{
                                    color:ALARM_COLOR[status]
                                }
                            }
                        });
                    //}
                }
                if(values){
                    console.log(values, xAixs);
                    require(['charts'],function(chart){
                        echart = chart;
                        _this.createOption(charType,values,xAixs).render();
                        overFlag = true;
                    })
                }
        },
        onChageField:function($el){
            console.log('change field', $el);
            overFlag=false;
            if($el){
                this.currentFieldElement = $el;
            }

            this.updateChart();
            // API.getChart({id:curids,field:this.getFieldValue($el)},curEvtType,listType);
        },
        getFieldValue:function($el){
            // var el = $el;
            if(!this.currentFieldElement){
                this.currentFieldElement = $($(".chart-wrap .switch-btn.active:visible")[0]);
            }
            if($el){
                this.currentFieldElement = $el;
            }
            //if($el && $el.length == 1){
                $(".chart-wrap h4").text(this.currentFieldElement.text());
            //}
            this.chartName = this.currentFieldElement.text();
            return this.currentFieldElement.attr('field');
        },
        createOption:function(type,data,xAixs){
            var _this = this,
                legendData = [],
                xAxis = [{
                    type : 'category',
                    data:xAixs?xAixs:["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20"]
                }],
                yAxis = [{
                    type : 'value',
                }],
                grid = {
                    //x:50,y:20,x2:50,y2:40,borderWidth:0
                },
                barColor = {
                    normal:'green',
                    notice:'#ffee2c',
                    alert:'#ff975e',
                    caution:'#f86464'
                },
                seriesLineCommonOption = {
                    symbolSize:8,
                    color:"green"
                },
                seriesBarCommonOption = {
                    symbolSize:8
                },
                series = [];

            if(data){
                series.push($.extend(true,seriesBarCommonOption,{
                    type:type,
                    data:data,
                    barMaxWidth:40
                }));

                _this.chartOption = {
                    series:[
                        {
                            name:_this.chartName,
                            type:type,
                            data:data,
                            barMaxWidth:30,
                            symbolSize:8,
                            lineStyle:{
                                normal:{
                                    color:"#000"
                                }
                            },
                            smooth:true
                        }
                    ],
                    xAxis:xAxis,
                    yAxis:yAxis,
                    grid:grid,
                    tooltip : {
                        trigger: 'axis',
                        axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                            type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                        },
                        formatter:"{b}<br/>当前值:{c}"
                    },
                    toolbox: {
                        show : true
                    }
                }
            }

            return this;
        },
        switchChartType :function(type) {
            charType = charType=='line'?'bar':'line';
            return this;
        },
        render:function(){
            ui.resizeChartBox();
            if(!this.chart){
                this.chart = echart.init($('#chart')[0])
            }

            this.chart.setOption(this.chartOption);
        }
    }

    $(window).resize(function(){
        if(navView && navView.chart){
            navView.chart.resize();
        }
    })

    return {
        init:function(_sys,_listType,_sub){
            console.log('chart init', _sys, _listType, _sub)
            sys = _sys;
            listType = _listType;
            sub = sub;
            curids="";


            $(".down").show();
            navView = new (Backbone.View.extend(chart_extobj))();

            $(".chart-wrap").off("click");
            $(".chart-wrap").on("click",".switch-btn",function(e){
                console.log('click on switch-btn');
                if($(this).hasClass('disabled')){
                    e.preventDefault();
                    return;
                }
                navView.onChageField($(this));
            })
            $(".chart-wrap").on("click",".shift-btn",function(){

                navView.switchChartType($(this));
                navView.onChageField();
            })

            return this;
        },
        isOver:function(value){
            return true;
            if(typeof value == 'undefined'){
                return !!overFlag;
            }else{
                overFlag = !!value;
            }
        },
        destory:function(){
            navView.stopListening();
            $(".down").hide();
        }
    };
})
