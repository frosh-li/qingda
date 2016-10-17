define(['require','api','ui','backbone'],function(require,API,ui,Backbone){
/*define(['require','api','ui','backbone','echarts'],function(require,API,ui,Backbone,echart){*/
    var navView,
        overFlag = false,
        echart,
        sys,listType,sub,curids="",curEvtType,
        charType="bar",
        ALARM_COLOR = {
            "0":"#13bd12",
            "1":"#ffee2c",
            "2":"#ff975e",
            "3":"#f76363"
        },
        ALARM_SYMBOL = {
            '0':'image://images/chart-normal.png',
            '1':'image://images/chart-notice.png',
            '2':'image://images/chart-alert.png',
            '3':'image://images/chart-caution.png'
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
            //列表更新
            // _this.listenTo(Backbone.Events,"listdata:update stationdata:get",function(data){
            //     curEvtType = "allids:get";
            //     overFlag=false;

            //     // API.getChart({id:curids,field:_this.getFieldValue()},curEvtType,listType);
            // });
            // //选择行变更
            // _this.listenTo(Backbone.Events,"row:select",function(data){
            //     curids = data.join(',');

            //     if(data.length>1){
            //         curEvtType = "allids:get";
            //         API.getChart({id:curids,field:_this.getFieldValue()},curEvtType,listType);
            //     }else{
            //         curEvtType = "id:get";
            //         API.getChart({id:curids,field:_this.getFieldValue()},curEvtType,listType);
            //     }
            // });

            _this.listenTo(Backbone.Events,"listdata:update stationdata:get",function(data){
                _this.updateChart(data);
            });
            _this.listenTo(Backbone.Events,"allids:get",function(data){

                if(data){
                    require(['charts'],function(chart){
                        var xAixs = [],values = [];
                        echart = chart;
                        if(listType == "caution"){
                            xAixs = ['红色','橙色','黄色'];
                            values = [
                                {
                                    value:data.red,
                                    symbol:ALARM_SYMBOL[3],
                                    symbolSize:14,
                                    itemStyle:{
                                        color:ALARM_COLOR[3],
                                        normal:{
                                            color:ALARM_COLOR[3]
                                        }
                                    }
                                },
                                {
                                    value:data.blue,
                                    symbol:ALARM_SYMBOL[2],
                                    symbolSize:14,
                                    itemStyle:{
                                        color:ALARM_COLOR[2],
                                        normal:{
                                            color:ALARM_COLOR[2]
                                        }
                                    }
                                },
                                {
                                    value:data.yellow,
                                    symbol:ALARM_SYMBOL[1],
                                    symbolSize:14,
                                    itemStyle:{
                                        color:ALARM_COLOR[1],
                                        normal:{
                                            color:ALARM_COLOR[1]
                                        }
                                    }
                                }

                            ]
                        }else{
                            $.each(data.list,function(i,d){
                                xAixs.push(d.sn_key||d.name);
                                values.push({
                                    value:d.value,
                                    symbol:ALARM_SYMBOL[d.status],
                                    symbolSize:14,
                                    itemStyle:{
                                        color:ALARM_COLOR[d.status],
                                        normal:{
                                            color:ALARM_COLOR[d.status]
                                        }
                                    }
                                });
                            })
                        }
                        _this.createOption(charType,values,xAixs).render();
                        overFlag = true;
                    })
                }
            });
            // _this.listenTo(Backbone.Events,"id:get",function(data){

            //     if(data && data.list){

            //         require(['charts'],function(chart){
            //             var xAixs = [],values = [],dataLen = data.list.length;
            //             for(var i=0;i<dataLen;i++){
            //                 xAixs.push(i+1);
            //             }

            //             echart = chart;

            //             $.each(data.list,function(i,d){
            //                 values.push({
            //                     value:d.value,
            //                     symbol:ALARM_SYMBOL[d.status],
            //                     symbolSize:14,
            //                     itemStyle:{
            //                         color:ALARM_COLOR[d.status],
            //                         normal:{
            //                             color:ALARM_COLOR[d.status]
            //                         }
            //                     }
            //                 });
            //             })
            //             _this.createOption(charType,values,xAixs).render();
            //             overFlag = true;
            //         })
            //     }
            // });
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

                for(var i = 0 ; i < _this.origindata.list.length; i++){
                    var cdata =  _this.origindata.list[i];
                        if(hash.indexOf('station') > -1){
                            xAixs.push(cdata.site_name+"-"+cdata.sid);
                        }else if(hash.indexOf("group") > -1){
                            xAixs.push(cdata.site_name+"-"+cdata.sid+"\n"+"组"+cdata.gid);
                        }else if(hash.indexOf("battery") > -1){
                            xAixs.push(cdata.site_name+"-"+cdata.sid+"\n"+"组"+cdata.gid+"-"+cdata.bid);
                        }

                        values.push({
                            value:cdata[col],
                            symbol:ALARM_SYMBOL[cdata.status],
                            symbolSize:14,
                            itemStyle:{
                                color:ALARM_COLOR[cdata.status],
                                normal:{
                                    color:ALARM_COLOR[cdata.status]
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
                    itemStyle:{
                        normal:{
                            lineStyle:{
                                color:"#889190",
                                width:1
                            }
                        }
                    }
                },
                seriesBarCommonOption = {
                    symbolSize:8,
                    color:'green'
                },
                series = [];

            if(data){
                series.push($.extend(true,seriesBarCommonOption,{
                    type:type,
                    data:data,
                    barMaxWidth:40
                }));

                _this.chartOption = {
                    color:['green'],
                    lineStyle:{
                        normal:{
                            color:'green'
                        }
                    },
                    series:[
                        {
                            name:_this.chartName,
                            type:type,
                            data:data,
                            barMaxWidth:30,
                            symbolSize:8
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
