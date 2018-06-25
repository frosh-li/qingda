define(["require","backbone","context","ui",'common', 'stationsinfoDialog','api',"stationsinfoDialog","map","blocks/list","blocks/nav","blocks/charts","blocks/listSearch","blocks/list","blocks/nav"],function(require,Backbone,context,ui,common, stationsinfoDialog,API){
    var curModules = [],
        maxLoadingTime = 1000,
        curLoadingTime = 0;
    window.navTree = null;

    function init(sys,pageType,sub,params){
        $("#endTime").datetimepicker("setDate", new Date());
        $("#beginTime").datetimepicker("setDate", new Date((new Date()) - 7*24*60*60*1000));
        var $stationPop = $(".stationPop");
        $("body").stopTime('irc');
        $stationPop.off('click').on('click',function(e){

            var id = navTree.getSites().ids;

            if(id < 0){
                alert('请选择站点');
                return;
            }
            for(var i = 0 ; i < id.length ; i++){
                id[i] = id[i]+"0000";
            }

            stationsinfoDialog.show(id.join(","));
        });

        $(".exportdata").hide();
        var roleid = JSON.parse(localStorage.getItem('userinfo')).role;
        var canedit = JSON.parse(localStorage.getItem('userinfo')).canedit;
        var area = JSON.parse(localStorage.getItem("userinfo")).area;
        if(roleid == 2 && canedit != 1){
            $(".list-edit-btn").hide();
            $(".list-del-btn").hide();
            $("#listBtns li.undis").hide();
        }
        /**
         * 1 超级管理员
         * 2 管理员
         * 3 观察员
         */
        if(roleid != 1){
            $(".manage.newstations").hide();
            $(".manage.ignores").hide();
        }else{
            if(sys == "manage"){
                $(".manage.newstations").show();
                $(".manage.ignores").show();
            }else{
                $(".manage.newstations").hide();
                $(".manage.ignores").hide();
            }
        }
        if(roleid == 3){
            $(".switch-btn.settings").hide();//
            $(".option.stationOption").hide();
            $(".option.groupOption").hide();
            $(".option.batteryOption").hide();
            
            //$(".manage.newstations").show();

        }
        if(roleid == 2){
            $("#addBMS").hide();
            // $("#addCompany").hide();
            $("#addBattery").hide();
            $("#addUps").hide();
            $("#addStation").hide();

            
            $(".settings.message").hide();

            $(".option.stationOption").hide();
            $(".option.groupOption").hide();
            $(".option.batteryOption").hide();


        }

        common.cookie.setCookie('otherOption','');
        if(roleid == 2 && area != "*"){
            $(".optionSetting.otherOption").hide();
            $.get('/api/index.php/param/getpara',function(str){
                var data = eval('('+ str +')');
                // console.log(data.response);
                if (data.response.code == 0){
                    var disOther = data.data.disOther == null ? 0 : data.data.disOther;
                    if (disOther == 1){
                        $('#otherOption').hide();
                        common.cookie.setCookie('otherOption','1');
                        // $(".optionSetting.otherOption").show();
                    }
                }
            });
        }
        if (roleid == 1){
            setTimeout(function(){
                $('#otherOption').show();
            },100);
            
        }

        Backbone.listenTo(Backbone.Events,"stat",function(data){
            $("#stat_login_time").html(data.loginTime);
            $("#stat_sys_uptime").html(data.startTime);
            $("#stat_manager").html(data.name);
        })

        API.stat();
        $("#logout").off("click").bind("click",function(){
            if(confirm("确定要退出系统吗？")){
                $.ajax({
                    url:"/api/index.php/login/loginOut",
                    type:"get",
                    success:function(){
                        localStorage.removeItem('userinfo');
                        window.location.href = "/";
                    }
                })
            }

        })

        var _arg = arguments,
            $navTreeWrap = $("#navTree"),
            $collectWrap = $("#collect").hide(),
            $collectIRWrap = $("#collectIRWrap").hide(),
            $durationWrap = $("#duration").hide(),
            $searchWrap = $(".search-jqtransform").hide();
            $stationPop = $('.stationPop');

        $('.cj-btn').click(function(){
            // 查询

        })



        $stationPopDialog = null;
        require(["dialogstationsinfo"],function(dialog){
            $stationPopDialog = dialog.init();
            // dialog && dialog.show(id.join(","));
        })

        $("#dataItem").html('');

        //ui.collectAuto();


        if("map" == sys){
            require(["map"],function(map){
                ui.resize();
                map.init();
            })
            return;
        }

        setTimeout(function(){
            var refreshpage = ['#/manage/station', '#/manage/group', '#/manage/battery', '#/manage/caution','#/manage/systemAlarm'];
            var time = parseInt($("#collectDuration").val());
            // 判断是否在实时页面
            var hash = window.location.hash;

            if(refreshpage.indexOf(hash) > -1
                    || hash.indexOf("manage/station")>-1 ){

                if(time
                    && localStorage.getItem("collecting") == 'true'){

                    $("body").addClass('collecting').everyTime(time+"s",'collect',API.collect);
                    $("#startCollectBtn").hide();
                    $(".tzcj").css('display','block');
                }else{
                    $("body").removeClass('collecting').stopTime('collect',API.collect);
                    $("#startCollectBtn").css('display','block');
                    $(".tzcj").hide();
                }

            }else{
                $("body").removeClass('collecting').stopTime('collect',API.collect);
                $("#startCollectBtn").css('display','none');
                $(".tzcj").hide();
            }
        },1000);


        common.loadTips.show("系统加载中，请稍后...");
        if(/^caution$/.test(pageType)){

            $("#dataItem").html($("#listTpl").html());
            ui.downHide(true);
            $(".atype").show();
            $(".stationPop").show();
            $collectWrap.show();
            if(pageType == 'battery'){
                $(".list-bottom.undis").show();
            }else{
                $(".list-bottom.upage").show();
            }

            ui.switchChartBtns(pageType);
            require(["blocks/list","blocks/nav","api"],function(list,nav,API){

                afterInit(sys,pageType,sub);

                if(!navTree){
                    refreshModules([nav],_arg);
                    nav.run(function(){
                        navTree=nav;

                        refreshModules([list],_arg);
                    });

                }else{

                    refreshModules([list],_arg);
                }

                API.getLinkingStationNum().getParam({},'refresh:get');

                isOver();
            })
        }

        if(/^(newstations|ignores)$/.test(pageType)){

            $("#dataItem").html($("#listTpl").html());
            ui.downHide(true);
            $collectWrap.show();
            $(".stationPop").hide();
            if(pageType == 'battery'){
                $(".list-bottom.undis").show();
            }else{
                $(".list-bottom.upage").show();
            }

            ui.switchChartBtns(pageType);
            require(["blocks/list","blocks/nav","api"],function(list,nav,API){

                afterInit(sys,pageType,sub);

                if(!navTree){
                    refreshModules([nav],_arg);
                    nav.run(function(){
                        navTree=nav;

                        refreshModules([list],_arg);
                    });

                }else{

                    refreshModules([list],_arg);
                }

                API.getLinkingStationNum().getParam({},'refresh:get');

                isOver();
            })
        }

        
        

        if(/^systemAlarm$/.test(pageType)){

            $("#dataItem").html($("#listTpl").html());
            ui.downHide(true);
            $collectWrap.show();
            $(".stationPop").show();
            if(pageType == 'battery'){
                $(".list-bottom.undis").show();
            }else{
                $(".list-bottom.upage").show();
            }

            ui.switchChartBtns(pageType);
            require(["blocks/list","blocks/nav","api"],function(list,nav,API){

                afterInit(sys,pageType,sub);

                if(!navTree){
                    refreshModules([nav],_arg);
                    nav.run(function(){
                        navTree=nav;

                        refreshModules([list],_arg);
                    });

                }else{

                    refreshModules([list],_arg);
                }

                API.getLinkingStationNum().getParam({},'refresh:get');

                isOver();
            })
        }



        if (/^(station|group|battery)$/.test(pageType)) {
            $("#dataItem").html($("#listTpl").html());
            ui.downShow(true);
        	$collectWrap.show();
            $(".stationPop").show();
            if(pageType == 'battery'){
                $(".list-bottom.undis").show();
            }else{
                $(".list-bottom.upage").show();
            }

            ui.switchChartBtns(pageType);
            require(["blocks/charts","blocks/list","blocks/nav","api"],function(chart,list,nav,API){

                afterInit(sys,pageType,sub);
                if(!navTree){
                    refreshModules([nav],_arg);
                    nav.run(function(){
                        navTree=nav;

                        refreshModules([list,chart],_arg);
                    });

                }else{

                    refreshModules([list,chart],_arg);
                }

                API.getLinkingStationNum().getParam({},'refresh:get');

                isOver();
            })
        }else if (/^(IRCollect)$/.test(pageType)) {
            $(".stationPop").hide();
            ui.downHide(true);
            $(".collect-jqtransform").jqTransform();

            $("#dataItem").html($("#listTpl").html());
        	$collectIRWrap.show();
            // $(".list-bottom.upage").show();
            require(["blocks/list","blocks/nav"],function(list,nav){
                refreshModules([nav,list],_arg);
                // collectPswdDialog.show();
                afterInit(sys,pageType,sub);
                if(!navTree){
                    nav.run();
                    navTree=nav;
                }

                API.getLinkingStationNum().getParam({},'refresh:get');
                
                isOver();
            })
        }else if (/^(limitation|baseinfo|equipment|option|adminConfig)$/.test(pageType)) {
            ui.downHide(true);
            $(".stationPop").hide();
            $("#dataItem").html($("#listTpl").html());
        	$durationWrap.show();

            require(["blocks/list","blocks/nav"],function(list,nav){
                afterInit(sys,pageType,sub);
                if(!navTree){
                    refreshModules([nav],_arg);
                    nav.run(function(){
                        navTree=nav;
                        refreshModules([list],_arg);
                    });
                }else{
                    refreshModules([list],_arg);
                }

                API.getLinkingStationNum().getParam({},'refresh:get');
                
                isOver();
            })
        }else if(/^(uilog)$/.test(pageType)){
            $(".stationPop").hide();
            $("#dataItem").html($("#listTpl").html());
            ui.downHide(true);

            $searchWrap.show();
            $searchWrap.jqTransform();
            $(".exportdata").hide();
            //$durationWrap.show();
            $(".list-bottom.upage").show();
            $(".report-caution-selector",$searchWrap).parents('.jqTransformSelectWrapper').hide()
            $(".reportCaution",$searchWrap).hide()
            require(["blocks/listSearch","blocks/list","blocks/nav"],function(listSearch,list,nav){
                afterInit(sys,pageType,sub);
                if(!navTree){
                    nav.run();
                    navTree=nav;
                }
                refreshModules([nav,listSearch,list],_arg);
                API.getLinkingStationNum().getParam({},'refresh:get');
                isOver();
            })
        }else if(/^runlog$/.test(pageType)){
            $("#dataItem").html($("#listTpl").html());
            $(".stationPop").hide();
            ui.downHide(true);
            //$searchWrap.show();
            //$searchWrap.jqTransform();
            $(".exportdata").hide();

            $(".list-bottom.upage").show();
            $(".report-caution-selector",$searchWrap).parents('.jqTransformSelectWrapper').hide()
            $(".reportCaution",$searchWrap).hide()
                require(["blocks/listSearch","blocks/list","blocks/nav"],function(listSearch,list,nav){
                    afterInit(sys,pageType,sub);    
                    if(!navTree){
                        refreshModules([nav],_arg);
                        nav.run(function(){
                            navTree=nav;
                            refreshModules([listSearch,list],_arg);
                                
                        });
                        
                    }else{
                        refreshModules([listSearch,list],_arg);
                        
                    }
                    API.getLinkingStationNum().getParam({},'refresh:get');
                    isOver();
                })    

        }else if (/^(qureyBattery|qureyStation|qureyGroup|qureyCaution)$/.test(pageType)) {
            $("#dataItem").html($("#listTpl").html());
            $(".stationPop").hide();
            ui.downShow(false);
            $searchWrap.show();
            $searchWrap.jqTransform();
            $(".exportdata").show();

            $(".list-bottom.upage").show();
            $(".report-caution-selector",$searchWrap).parents('.jqTransformSelectWrapper').hide()
            $(".reportCaution",$searchWrap).hide()
            if(/^qureyCaution$/.test(pageType)){
                $(".report-caution-selector",$searchWrap).parents('.jqTransformSelectWrapper').show()
                $(".reportCaution",$searchWrap).show();
                $searchWrap.jqTransform();
                ui.downHide(true);
                require(["blocks/listSearch","blocks/list","blocks/nav"],function(listSearch,list,nav){
                    afterInit(sys,pageType,sub);
                    if(!navTree){
                        refreshModules([nav],_arg);
                        nav.run(function(){
                            navTree=nav;
                            refreshModules([listSearch,list],_arg);
                        });
                        
                    }else{
                        refreshModules([listSearch,list],_arg);
                    }

                    API.getLinkingStationNum().getParam({},'refresh:get');

                    isOver();
                })
            }else{
                require(["blocks/listSearch","blocks/list","blocks/nav"],function(listSearch,list,nav){
                    afterInit(sys,pageType,sub);    
                    if(!navTree){
                        refreshModules([nav],_arg);
                        nav.run(function(){
                            navTree=nav;
                            refreshModules([listSearch,list],_arg);
                                
                        });
                        
                    }else{
                        refreshModules([listSearch,list],_arg);
                        
                    }

                    API.getLinkingStationNum().getParam({},'refresh:get');

                    isOver();
                })    
            }
            ui.downShow(false);
            
        }else if (/^(reportUilog|chargeOrDischarge|deviationTrend|batteryLife|reportCaution|reportIgnores)$/.test(pageType)) {
            ui.downHide(true);
            $(".stationPop").hide();
            $(".exportdata").hide();
            $("#dataItem").html($("#listTpl").html());
            $searchWrap.hide();
            $searchWrap.jqTransform();
            if(pageType != "reportCaution"){
                $(".report-caution-selector",$searchWrap).parents('.jqTransformSelectWrapper').hide()
                $(".reportCaution",$searchWrap).hide()
            }else{
                $(".report-caution-selector",$searchWrap).parents('.jqTransformSelectWrapper').show()
                $(".reportCaution",$searchWrap).show()
            }

            $(".list-bottom.upage").hide();

            require(["blocks/listSearch","blocks/list","blocks/nav"],function(listSearch,list,nav){
                refreshModules([nav,listSearch,list],_arg);
                afterInit(sys,pageType,sub);
                if(!navTree){
                    nav.run();
                    navTree=nav;
                }
                API.getLinkingStationNum().getParam({},'refresh:get');

                isOver();
            })
        }else if(/^(stationOption)$/.test(pageType)) {
            $(".stationPop").hide();
            $("#dataItem").html($("#listTpl").html());
            ui.downHide(true);

            $searchWrap.show();
            $searchWrap.jqTransform();
            $(".exportdata").show();
            $(".list-bottom.upage").show();
            $(".report-caution-selector",$searchWrap).parents('.jqTransformSelectWrapper').hide()
            $(".reportCaution",$searchWrap).hide()
            require(["blocks/listSearch","blocks/list","blocks/nav"],function(listSearch,list,nav){
                
                afterInit(sys,pageType,sub);
                if(!navTree){
                    refreshModules([nav],_arg);
                    nav.run(function(){
                        navTree=nav;   
                        refreshModules([listSearch,list],_arg); 
                    });
                    
                }else{
                    refreshModules([listSearch,list],_arg); 
                }

                isOver();
            })
        
        }else if(/^(manager|message|optionSetting|cautionEquipmentSetting|equipmentSetting|update|limitationSetting)$/.test(pageType)) {
            ui.downHide(true);
            $(".stationPop").hide();
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
                    API.getLinkingStationNum().getParam({},'refresh:get');

                    isOver();
                    var roleid = JSON.parse(localStorage.getItem('userinfo')).role;
                    if (roleid > 1) $('#systemOption').hide();

                    if (common.cookie.getCookie('otherOption') == 1){
                        $(".optionSetting.otherOption").show();
                    }
                })
            }else{
                require(["blocks/list","blocks/nav"],function(list,nav) {
                    $("#dataItem").html($("#listTpl").html());
                    
                    afterInit(sys,pageType,sub);
                    if(!navTree){
                        refreshModules([nav],_arg);
                        nav.run(function(){
                            navTree=nav;
                            refreshModules([list],_arg);
                        });
                        
                    }else{
                        refreshModules([list],_arg);
                    }
                    API.getLinkingStationNum().getParam({},'refresh:get');

                    isOver();
                })
            }

        }else if(/^(stationInfo)$/.test(pageType)) {
            $(".stationPop").hide();
            ui.downHide(true);
            if(sub=="tree"){//树形结构图
                require(["blocks/customTree","blocks/nav"],function(tree,nav) {
                    
                    afterInit(sys,pageType,sub);
                    if(!navTree){
                        refreshModules([nav],_arg);
                        nav.run(function(){
                            navTree=nav;   
                            refreshModules([tree],_arg); 
                        });
                        
                    }else{
                        refreshModules([tree],_arg); 
                    }

                    API.getLinkingStationNum().getParam({},'refresh:get');

                    isOver();
                })
            }else{
                require(["blocks/listSearch","blocks/list","blocks/nav"],function(listSearch,list,nav) {
                    $("#dataItem").html($("#listTpl").html());
                    
                    afterInit(sys,pageType,sub);
                    if(!navTree){
                        refreshModules([nav],_arg);
                        nav.run(function(){
                            navTree=nav;
                            refreshModules([listSearch,list],_arg);
                        });
                        
                    }else{
                        refreshModules([listSearch,list],_arg);
                    }

                    API.getLinkingStationNum().getParam({},'refresh:get');

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
        var ctotals_array = [
            '#/settings/stationInfo/batterys',
            '#/settings/stationInfo/stationSituation',
            '#/settings/stationInfo/upsInfo',
            '#/settings/optionSetting/stationOption',
            '#/settings/optionSetting/groupOption',
            '#/settings/optionSetting/batteryOption'
        ];
        if(ctotals_array.indexOf(window.location.hash) > -1){
            $(".list-bottom.ctotals").show();
        
        }else{
            $(".list-bottom.ctotals").hide();
        }
        //当为超管时，在设置面板中，显示报警级别，远程升级，其它人隐藏
        var roleid = JSON.parse(localStorage.getItem('userinfo')).role;
        console.log(roleid);
        if (location.hash.indexOf('#/settings/') != -1 && roleid > 1){
            $('#limitationSetting').hide();
            $('#update').hide();
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
    function filterBoxCookie(){
        var station = "Current,Voltage,Temperature,Humidity,Lifetime,Capacity,ChaState,record_time,Groups,GroBats,ups_maintain_date,ups_power";
        var stationStr = common.cookie.getCookie('stationCols');
        if (stationStr == null || stationStr == undefined || stationStr == ""){
            common.cookie.setCookie('stationCols',station);
        }
        var stationStrQuery = common.cookie.getCookie('qurey_stationCols');
        if (stationStrQuery == null || stationStrQuery == undefined || stationStrQuery == ""){
            common.cookie.setCookie('qurey_stationCols',station);
        }

        var group = "Voltage,Current,Temperature,Humidity,Avg_U,Avg_T,Avg_R,record_time,GroBats";
        var groupStr = common.cookie.getCookie('groupCols');
        if (groupStr == null || groupStr == undefined || groupStr == ""){
            common.cookie.setCookie('groupCols',group);
        }
        var groupStrQuery = common.cookie.getCookie('qurey_groupCols');
        if (groupStrQuery == null || groupStrQuery == undefined || groupStrQuery == ""){
            common.cookie.setCookie('qurey_groupCols',group);
        }

        var battery = "Voltage,Temperature,Resistor,Dev_U,Dev_T,Dev_R,Lifetime,Capacity,record_time";
        var batteryStr = common.cookie.getCookie('batteryCols');
        if (batteryStr == null || batteryStr == undefined || batteryStr == ""){
            common.cookie.setCookie('batteryCols',battery);
        }
        var batteryStrQuery = common.cookie.getCookie('qurey_batteryCols');
        if (batteryStrQuery == null || batteryStrQuery == undefined || batteryStrQuery == ""){
            common.cookie.setCookie('qurey_batteryCols',battery);
        }

        var caution = "R,Y,O";
        var cautionStr = common.cookie.getCookie('cautionCols');
        if (cautionStr == null || cautionStr == undefined || cautionStr == ""){
            common.cookie.setCookie('cautionCols',caution);
        }
    }
    return {
        init:function(sys,pageType,sub,ids){
            filterBoxCookie();
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
