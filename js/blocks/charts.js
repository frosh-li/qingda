define(['require','api','ui','backbone'],function(require,API,ui,Backbone){
/*define(['require','api','ui','backbone','echarts'],function(require,API,ui,Backbone,echart){*/
    var navView,
        overFlag = false,
        echart,
        sys,listType,sub,curids="",curEvtType,
        charType="line",
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
        initialize:function(data){
            var _this = this;
            //列表更新
            _this.listenTo(Backbone.Events,"listdata:update stationdata:get",function(data){
                curEvtType = "allids:get";
                overFlag=false;
                console.log(data);
                console.log(curids);
                API.getChart({id:curids,field:_this.getFieldValue()},curEvtType,listType);
            });
            //选择行变更
            _this.listenTo(Backbone.Events,"row:select",function(data){
                curids = data.join(',');
                if(data.length>1){
                    curEvtType = "allids:get";
                    API.getChart({id:curids,field:_this.getFieldValue()},curEvtType,listType);
                }else{
                    curEvtType = "id:get";
                    API.getChart({id:curids,field:_this.getFieldValue()},curEvtType,listType);
                }
            });
            _this.listenTo(Backbone.Events,"chart:update",function(data){
                console.log(curids);
                if(data){
                    console.log('chart:update');
                    require(['charts'],function(chart){
                        echart = chart;
                        _this.createOption(data).render();
                        overFlag = true;
                    })
                }
            });
            _this.listenTo(Backbone.Events,"allids:get",function(data){
                console.log('allids get', data);
                if(data && data.list){
                    require(['charts'],function(chart){
                        var xAixs = [],values = [];
                        echart = chart;
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
                        _this.createOption(charType,values,xAixs).render();
                        overFlag = true;
                    })
                }
            });
            _this.listenTo(Backbone.Events,"id:get",function(data){
                console.log(curids);
                if(data && data.list){
                    console.log(data.list);
                    require(['charts'],function(chart){
                        var xAixs = [],values = [],dataLen = data.list.length;
                        for(var i=0;i<dataLen;i++){
                            xAixs.push(i+1);
                        }

                        echart = chart;

                        $.each(data.list,function(i,d){
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
                        _this.createOption(charType,values,xAixs).render();
                        overFlag = true;
                    })
                }
            });
        },
        clear:function(){

        },
        getLevel:function(value,range){

        },
        onChageField:function($el){
            overFlag=false;
            API.getChart({id:curids,field:this.getFieldValue($el)},curEvtType,listType);
        },
        getFieldValue:function($el){
            return $el?$el.attr('field'):$(".chart-wrap .switch-btn.active:visible").attr('field');
        },
        createOption:function(type,data,xAixs){
            var _this = this,
                legendData = [],
                xAxis = [{
                    type : 'category',
                    boundaryGap : true,
                    axisLine:false,
                    axisTick:false,
                    splitLine:false,
                    data:xAixs?xAixs:["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20"]
                }],
                yAxis = [{
                    type : 'value',
                    splitNumber:3,
                    axisLine:false,
                    axisTick:false,
                    splitLine:{
                        lineStyle:{
                            color:['#efefef'],
                            type:"dashed"
                        }
                    }
                }],
                grid = {
                    x:50,y:20,x2:50,y2:40,borderWidth:0
                },
                barColor = {
                    normal:'#17bd13',
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
                    symbolSize:8
                },
                series = [];

            if(data){
                series[0] = $.extend(true,seriesBarCommonOption,{
                    type:type,
                    data:data,
                    barMaxWidth:40
                })

                _this.chartOption = {
                    lineStyle:{
                        normal:{
                            color:'#858e8d'
                        }
                    },
                    series:series,
                    xAxis:xAxis,
                    yAxis:yAxis,
                    grid:grid,
                    legendData:legendData
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
            this.chart = echart.init($('#chart')[0]);
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
            sys = _sys;
            listType = _listType;
            sub = sub;
            curids="";


            $(".down").show();
            navView = new (Backbone.View.extend(chart_extobj))();

            $(".chart-wrap").off("click");
            $(".chart-wrap").on("click",".switch-btn",function(){
                navView.onChageField($(this));
            })
            $(".chart-wrap").on("click",".shift-btn",function(){
                navView.switchChartType($(this));
                navView.onChageField($(".switch-btn.active"),$(".chart-wrap"));
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