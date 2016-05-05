define(["require","backbone","context","ui",'common', 'stationsinfoDialog','api'],function(require,Backbone,context,ui,common, stationsinfoDialog,API){
    var curModules = [],navTree=null,
        maxLoadingTime = 1000,
        curLoadingTime = 0;

    function init(sys,pageType,sub,params){

        Backbone.listenTo(Backbone.Events,"stat",function(data){
            $("#stat_login_time").html(data.loginTime);
            $("#stat_sys_uptime").html(data.startTime);
            $("#stat_manager").html(data.name);
        })
        
        API.stat();
        $("#logout").click(function(){
            window.location = "#/login";
        })

        var _arg = arguments,
            $navTreeWrap = $("#navTree"),
            $collectWrap = $("#collect").hide(),
            $collectIRWrap = $("#collectIR").hide(),
            $durationWrap = $("#duration").hide(),
            $searchWrap = $(".search-jqtransform").hide();
            $stationPop = $('.stationPop');

        $stationPop.click(function(e){
            console.log('click on stationPop');
            var id = navTree.getSites().ids;
            if(id < 0){
                alert('请选择站点');
                return;
            }
            for(var i = 0 ; i < id.length ; i++){
                id[i] = id[i]+"0000";
            }
            console.log('show stationsinfoDialog', id)
            //return function(){
                stationsinfoDialog.show(id.join(","));
            //}
        });
        $("#dataItem").html('');

        ui.collectAuto();


        if("map" == sys){
            navTree=null;
            require(["map"],function(map){
                ui.resize();
                map.init();
            })
            return;
        }

        common.loadTips.show("系统加载中，请稍后...");
        console.log(arguments);
        if (/^(station|group|battery|caution)$/.test(pageType)) {
            $("#dataItem").html($("#listTpl").html());
            ui.downShow(true);
        	$collectWrap.show();
            if(pageType == 'battery'){
                $(".list-bottom").show();
            }
            ui.switchChartBtns(pageType);
            require(["blocks/charts","blocks/list","blocks/nav","api"],function(chart,list,nav,API){
                
                afterInit(sys,pageType,sub);

                if(!navTree){
                    refreshModules([nav],_arg);
                    nav.run(function(){
                        navTree=nav;
                        console.log('my nav', nav);
                        refreshModules([list,chart],_arg);        
                    });
                    
                }else{
                    console.log('refresh node');
                    refreshModules([list,chart],_arg);    
                }
                
                API.getLinkingStationNum().getParam({},'refresh:get');

                isOver();
            })
        }else if (/^(IRCollect)$/.test(pageType)) {
            $("#dataItem").html($("#listTpl").html());
        	$collectIRWrap.show();
            require(["blocks/charts","blocks/list","blocks/nav","dialog-collectPswdDialog"],function(chart,list,nav){
                refreshModules([nav,list,chart,collectPswdDialog],_arg);
                afterInit(sys,pageType,sub);
                if(!navTree){
                    nav.run();
                    navTree=nav;
                }

                isOver();
            })
        }else if (/^(limitation|uilog|baseinfo|equipment|option|runlog|adminConfig)$/.test(pageType)) {
            ui.downHide(true);
            $("#dataItem").html($("#listTpl").html());
        	$durationWrap.show();
            require(["blocks/list","blocks/nav"],function(list,nav){
                refreshModules([nav,list],_arg);
                afterInit(sys,pageType,sub);
                if(!navTree){
                    nav.run();
                    navTree=nav;
                }

                isOver();
            })
        }else if (/^(qureyBattery|qureyStation|qureyGroup|qureyCaution)$/.test(pageType)) {
            $("#dataItem").html($("#listTpl").html());
            ui.downShow(true);
            ui.switchChartBtns(pageType);
        	$durationWrap.show();
            require(["blocks/charts","blocks/list","blocks/nav"],function(chart,list,nav){
                refreshModules([nav,list,chart],_arg);
                afterInit(sys,pageType,sub);
                if(!navTree){
                    nav.run();
                    navTree=nav;
                }

                isOver();
            })
        }else if (/^(reportUilog|chargeOrDischarge|deviationTrend|batteryLife|reportCaution)$/.test(pageType)) {
            ui.downHide(true);
            $("#dataItem").html($("#listTpl").html());
            $searchWrap.show();
            $searchWrap.jqTransform();
            if(pageType != "reportCaution"){
                $(".report-caution-selector",$searchWrap).parents('.jqTransformSelectWrapper').hide()
                $(".reportCaution",$searchWrap).hide()
            }else{
                $(".report-caution-selector",$searchWrap).parents('.jqTransformSelectWrapper').show()
                $(".reportCaution",$searchWrap).show()
            }
            require(["blocks/listSearch","blocks/list","blocks/nav"],function(listSearch,list,nav){
                refreshModules([nav,listSearch,list],_arg);
                afterInit(sys,pageType,sub);
                if(!navTree){
                    nav.run();
                    navTree=nav;
                }

                isOver();
            })
        }else if(/^(manager|message|optionSetting|cautionEquipmentSetting|equipmentSetting|update|limitationSetting)$/.test(pageType)) {
            ui.downHide(true);

            if("otherOption" == sub){
                require(["blocks/nav","api"],function(nav,API) {
                    $("#dataItem").html($("#otherOptionEditTpl-tpl").html());
                    refreshModules([nav],_arg);
                    afterInit(sys,pageType,sub);
                    if(!navTree){
                        nav.run();
                        navTree=nav;
                    }
                    API.getParam({},"otherOption:get");

                    isOver();
                })
            }else{
                require(["blocks/list","blocks/nav"],function(list,nav) {
                    $("#dataItem").html($("#listTpl").html());
                    refreshModules([nav,list],_arg);
                    afterInit(sys,pageType,sub);
                    if(!navTree){
                        nav.run();
                        navTree=nav;
                    }

                    isOver();
                })
            }

        }else if(/^(stationInfo)$/.test(pageType)) {
            ui.downHide(true);
            if(sub=="tree"){//树形结构图
                require(["blocks/customTree","blocks/nav"],function(tree,nav) {
                    refreshModules([nav,tree],_arg);
                    afterInit(sys,pageType,sub);
                    if(!navTree){
                        nav.run();
                        navTree=nav;
                    }
                    console.log(navTree);
                    isOver();
                })
            }else{
                require(["blocks/listSearch","blocks/list","blocks/nav"],function(listSearch,list,nav) {
                    $("#dataItem").html($("#listTpl").html());
                    refreshModules([nav,listSearch,list],_arg);
                    afterInit(sys,pageType,sub);
                    if(!navTree){
                        nav.run();
                        navTree=nav;
                    }

                    isOver();

                })
            }
        }

        /*switch (pageType){
            case 'station'://实时：站数据
            case 'group'://实时：组数据
            case 'battery'://实时：电池数据
            case 'caution'://实时：报警数据


                break;

            case 'qureyStation'://查询：站数据
            case 'qureyGroup'://查询：组数据
            case 'qureyBattery'://查询：电池数据
            case 'qureyCaution'://查询：报警数据
            case 'uilog'://查询：UI日志
            case 'baseinfo'://查询：基本信息
            case 'limitation'://查询：门限
            case 'runlog'://查询：运行日志
            case 'option'://查询：参数
            case 'adminConfig'://查询：管理员配置
            case 'equipment'://查询：外空设备
            case 'IRCollect'://查询：内阻采集

            case 'reportCaution'://报表：报警数据
            case 'batteryLife'://报表：电池使用年限
            case 'chargeOrDischarge'://报表：充放电统计
            case 'reportUilog'://报表：UI日志
            case 'deviationTrend'://报表：偏离趋势

            case 'stationInfo'://设置：站信息
            case 'manager'://设置：管理员
            case 'stationTree'://设置：树形结构图
            case 'message'://设置：短信
            case 'optionSetting'://设置：参数
            case 'limitationSetting'://设置：门限
            case 'cautionEquipmentSetting'://设置：报警设备
            case 'systemInfo'://设置：基本信息
            case 'chargeOrDischargevSetting'://设置：充放电维护信息
            case 'equipmentSetting'://设置：外控设备
            case 'update'://设置：升级
        }*/
    }
    //页面是否加载完毕
    function isOver(){
        var over = !!curModules.length;
        $.each(curModules,function(i,m){
            if(!m.isOver()){
                over = false;
                return false;
            }
        })
        if(over || curLoadingTime>maxLoadingTime){
            common.loadTips.close();
            curLoadingTime = 0;
        }else{
            curLoadingTime+=200;
            $('body').oneTime('0.2s',function(){
                isOver();
            });
        }
    }
    function refreshModules(modules,arg){
    	var adds = [];
        destoryModules();
        addModules(modules,arg);
    }
    function destoryModules(){
        $.each(curModules,function(i,m){
            m && m.destory && m.destory();
        })
        curModules = [];
    }
    function addModules(modules,arg){
        var c = modules.shift();
        if(c&&c.init){
            c.init.apply(c,arg);
            curModules.push(c);
            addModules(modules,arg);    
        }
        // $.each(modules,function(i,m){
        //     if( m && m.init ){
        //         m.init.apply(m,arg);
        //         curModules.push(m);
        //     }
        // })
    }

    function afterInit(sys,pageType,sub){
        ui.resize().switchNav(sys,pageType,sub);
    }
    return {
        init:function(sys,pageType,sub,ids){
            pagetype = pageType;
            init(sys,pageType,sub,ids);
            if(ids){

            }
            stationsinfoDialog.init();
            return this;
        },
        refresh:function(sys,pageType,sub){
            pagetype = pageType;
            init(sys,pageType,sub);
        }
    };
})