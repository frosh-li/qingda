define(function(require){
    var Backbone = require('backbone'),
        API = require('api'),
        context = require('context'),
        common = require('common'),

        isMonitoring = false;

    var ui = Backbone.View.extend({
        el:"body",
        events:{
            "click #addStation":"onAddStation",
            "click #addBattery":"onAddBattery",
            "click #addDevice":"onAddDevice",
            "click #addUps":"onAddUPS",
            "click #addBMS":"onAddBMS",
            "click #addCompany":"onAddCompany",
            "click #addMessage":"onAddMessage",
            "click #addPersonal":"onAddPersonal",
            "click #addStationOption":"onAddStationOption",
            "click #singleChart":"showSingleChart",
            "click #mutipleChart":"showMutipleChart",
            "click #saveOtherOptionBtn":"saveOtherOption",
            "click .left-hide":"leftHide",
            "click .yx-show":"leftShow",
            "click .zoom":"upShow",
            "click .bottom-hide":"downHide",
            "click .bottom-show":"downShow",
            "click .switch-btn a" :"switchBtn",
            "click .ggsj":"onUpadtaTime",
            "click #startCollectBtn":"startCollect",
            "click #navSearchBtn":"onSearchNav",
            "click .tzcj":"stopCollect",
            "click .title-list .switch-btn i":"showItemsLayer",
            "click .cj-btn":"onSearch",
            "click .exportdata": "onExportCSV",
            "click #searchBtn span span":"onSearch",
            "click #switchUser": "switchUser",
            "click #startCollectR": "startCollectR",
            "click #about": "about"
            //"click .stationPop": "stationPop"
        },
        initialize:function(){
            var _this = this;
            _this.listenTo(Backbone.Events,"refreshtime:update",function(data){
                alert("设置成功");
                if(_this.isCollecting()){
                    _this.stopCollect().startCollect();
                }
            });
            _this.listenTo(Backbone.Events,"refresh:get",function(data){
                $("#collectDuration").val(data.refresh)
            });
            _this.listenTo(Backbone.Events,"linknum:get",function(data){

                $("#linkingNum").html(data.online)
                $("#unlinkNum").html(data.offline)
                $("#collectDuration").val(data.refresh);
                if(data.dismap == 0){
                    $("#map-switch").hide();
                }else{
                    $("#map-switch").show();
                }
                // 更新灯的状态

                if(data.voice_on_off == "1"){
                    $("#alarm_sound").removeClass("graylight").addClass("grelight")
                }else{
                    $("#alarm_sound").removeClass("grelight").addClass("graylight");
                }
                if(data.light_on_off == "1"){
                    $("#alarm_light").removeClass("graylight").addClass("grelight")
                }else{
                    $("#alarm_light").removeClass("grelight").addClass("graylight");
                }

                if(data.upiao == "1"){
                    $("#alarm_collecting").removeClass("graylight").addClass("grelight")
                }else{
                    $("#alarm_collecting").removeClass("grelight").addClass("graylight");
                }

                if(data.sms_on_off == "1"){
                    $("#alarm_sms").removeClass("graylight").addClass("grelight")
                }else{
                    $("#alarm_sms").removeClass("grelight").addClass("graylight");
                }
                if(data.email_on_off == "1"){
                    $("#alarm_mail").removeClass("graylight").addClass("grelight")
                }else{
                    $("#alarm_mail").removeClass("grelight").addClass("graylight");
                }




                //$("#alarm_sound").removeClass("graylight").removeClass("")
            });
            _this.listenTo(Backbone.Events,"otherOption:get",function(data){
                require(["common"],function(common){
                    common.setFormValue($("#otherOptionEdit"),data);
                    $("#otherOptionEdit .jqtransform").jqTransform();
                })
            });
            _this.listenTo(Backbone.Events,"param:update",function(data){
                if(data.dismap == 0){
                    $("#map-switch").hide();
                }else{
                    $("#map-switch").show();
                }
                if(data.sms_on_off == "1"){
                    $("#alarm_sms").removeClass("graylight").addClass("grelight");
                    // 发送消息给软件
                    API.sendAllMsg();
                }else{
                    $("#alarm_sms").removeClass("grelight").addClass("graylight");
                }
                if(data.email_on_off == "1"){
                    $("#alarm_mail").removeClass("graylight").addClass("grelight")
                }else{
                    $("#alarm_mail").removeClass("grelight").addClass("graylight");
                }

                if(data.voice_on_off == "1"){
                    $("#alarm_sound").removeClass("graylight").addClass("grelight")
                }else{
                    $("#alarm_sound").removeClass("grelight").addClass("graylight");
                    document.getElementById("alertmusic").pause();
                }
                if(data.light_on_off === "1"){
                    $("#alarm_light").removeClass("graylight").addClass("grelight")
                }else{
                    $("#alarm_light").removeClass("grelight").addClass("graylight");
                }

                alert("修改成功")
            });


            _this.listenTo(Backbone.Events,"monitoring:start",function(data){
                console.log('monitoring', data);
                if(data.totals && data.totals > 0){
                    $(".baojing").css('display','block');
                    if(data.voice_on_off == "1"){
                        document.getElementById("alertmusic").play();
                    }else{
                        document.getElementById("alertmusic").pause();
                    }
                }else{
                    $(".baojing").css('display','none')
                    document.getElementById("alertmusic").pause();
                }
                $(".baojing .bg").html(data.totals||0);
            });
            _this.listenTo(Backbone.Events,"monitoring:start:fail",function(data){
                if(data.totals && data.totals > 0){
                    $(".baojing").css('display','block');
                    if(data.voice_on_off == "1"){
                        document.getElementById("alertmusic").play();
                    }else{
                        document.getElementById("alertmusic").pause();
                    }
                }else{
                    $(".baojing").css('display','none');
                    document.getElementById("alertmusic").pause();
                }
                $(".baojing .bg").html(data.totals||0);
            });
            _this.listenTo(Backbone.Events,"station:next",function(data){
                _this.showBatteryEditDialog(false,data);
            });
            _this.listenTo(Backbone.Events,"battery:next",function(data){
                _this.showUPSEditDialog(false,data);
            });
        },
        switchUser: function(){

            require(["dialoglogin"],function(dialog){
                dialog && dialog.show();
            })

        },

        about: function(){

            require(["dialogabout"],function(dialog){
                dialog.init();
                dialog && dialog.show();
            })

        },

        startCollectR: function(){
            var pwd = $("#cj_password").val();
            if(pwd != "bms"){
                alert('请输入正确的采集密码');
                $("#cj_password").val("");
                return;
            }

            require(['blocks/nav'], function(nav){

                var navData = nav.getSites();
                var ids;
                if(this.ids && this.ids.sid){
                    ids = this.ids.sid;
                }else{
                    ids = navData.ids.join(",");
                }

                if(ids.split(",").length > 1){
                    alert('只能勾选一个站点');
                    return;
                }

                var batteryId = nav.getBatteryIds();

                var battyerids = batteryId?batteryId.ids.join(','):'';
                $('#cj_password').val("");
                $.ajax({
                    url:"http://"+window.location.host+":3000"+"/irc",
                    type:"post",
                    data:{
                        batterys:battyerids
                    }
                })


                API.updateCollect({
                    stationid: ids,
                    batterys: battyerids
                },"rCollect:start")

            })

            // Backbone.Events.trigger('startCollect');
        },

        onSearch: function(){
            Backbone.Events.trigger('search:done');
        },
        onExportCSV: function(){
            Backbone.Events.trigger('export:done');
        },
        showItemsLayer:function(evt){

            var $el = $(evt.currentTarget),
                position = $el.offset(),
                type = /qurey/.test(window.location.href) ? 'qurey_'+$el.attr('for'):$el.attr('for'),
                customCols = common.cookie.getCookie(type+'Cols'),
                allCols = context.getListCols(type).concat([]),
                retCols=[],
                htmls='';

            if(customCols){
                customCols = customCols.split(',');
            }
            if(!customCols || customCols.length == 0){
                customCols = allCols;
            }
            console.log(allCols, customCols);
            $.each(allCols,function(i,col){
                if(common.inArray(col.data,customCols)){
                    col.ischeck="checked"
                }else{
                    col.ischeck=""
                }
                htmls += _.template('<label><input name="cols" type="checkbox" <%= ischeck %> key="<%= data %>"/><%= title %></label>')(col);
            })
            $("<div class='config-item-list'>"+ htmls +"</div>").dialog({
                dialogClass:"submenu",
                modal:true,
                width:600,
                buttons: [
                    {
                        text:'全选',
                        icons:{
                            primary:'ui-icon-heart'
                        },
                        click: function(){
                            $("[type=checkbox]",$(this)).each(function(i,el){
                                $(el).attr('checked','checked');
                            })
                        }
                    },
                    {
                        text: "确认",
                        icons: {
                            primary: "ui-icon-heart"
                        },
                        click: function() {
                            var selectCols='';
                            $("[type=checkbox]:checked",$(this)).each(function(i,el){
                                selectCols += $(el).attr('key')+',';
                            })

                            if(!selectCols){
                                alert('请至少选择一列展示');
                                return;
                            }

                            common.cookie.setCookie(type+'Cols', selectCols.replace(/,$/,''));
                            console.log('start trigger', type+'ColsChange');
                            Backbone.Events.trigger(type+'ColsChange');
                            $( this ).dialog( "close" );
                        }
                    }
                ]
            });
            $(".ui-dialog").css({
                top:position.top+$el.height(),
                left:position.left+$el.width()
            })

        },
        switchBtn:function(evt){
            var $el = $($(evt.currentTarget).parents(".switch-btn")),
                $els = $el.siblings('.switch-btn');
            $els.removeClass('active');
            $el.addClass('active');
        },
        showUnsolveDialog:function(id){
            require(["dialogresolveCaution"],function(dialog){
                dialog && dialog.show(id);
            })
        },
        /**
         * 左侧树形导航
         */
        onSearchNav:function(){
            var keyword = $("#navSearchKeyword").val();
            API.getNavData(function(){},{
                key:keyword
            });
        },
        /**
         * 更改采集时间
         */
        onUpadtaTime:function(){
            var time = parseInt($("#collectDuration").val());
            if(time < 15){
                alert("最小间隔为15");
                return;
            }
            var roleid = JSON.parse(localStorage.getItem('userinfo')).role;
            if(roleid == 3){
                alert("您没有权限修改最小间隔时间");
                return;
            }
            if(time){
                API.updateParam({
                    refresh:time
                },"refreshtime:update")
            }
        },
        /**
         * 定时器---采集
         */
        isCollecting:function(){
            return localStorage.getItem('collecting') === 'true';
        },
        startCollect:function(){
            // if(this.isCollecting()){return;}
            var time = parseInt($("#collectDuration").val());
            // 判断是否在实时页面
            var hash = window.location.hash;
            var refreshpage = ['#/manage/station', '#/manage/group', '#/manage/battery', '#/manage/caution'];
            if(time){
                if(refreshpage.indexOf(hash) > -1){
                    $("body").addClass('collecting').everyTime(time+"s",'collect',API.collect);
                }else{
                    $("body").removeClass('collecting').stopTime('collect',API.collect);
                }
                $("#startCollectBtn").hide();
                $(".tzcj").css('display','block');
                localStorage.setItem('collecting','true');
            }

            return this;
        },
        collectAuto: function(){
            if(this.isCollecting()){
                this.startCollect();
            }else{
                this.stopCollect();
            }
        },
        stopCollect:function(){
            $("body").removeClass('collecting').stopTime('collect',API.collect);
            $("#startCollectBtn").css('display','block');
            $(".tzcj").hide();
            localStorage.setItem('collecting','false');
            return this;
        },
        /**
         * 定时器---获取报警信息
         */
        startGetCaution:function(){
            console.log('start interval 30');
            if(isMonitoring){return}
            isMonitoring = true;
            $("body").everyTime('30s','monitoring',function(){
                //API.getMapData(false,'realtime:mapdata');
                API.getNavData();
                API.getLinkingStationNum();
                API.getCautionsData(false,'monitoring:start',true);
            });
        },
        stopGetCaution:function(){
            isMonitoring = false;
            $("body").stopTime('monitoring');
        },
        /**
         * 添加站点
         */
        onAddStation:function(){
            this.showStationEditDialog();
        },
        showStationEditDialog:function(id){
            require(["dialogstationEdit"],function(dialog){
                dialog && dialog.show(id);
            })
        },

        showSendDataDialog:function(sn){
            require(["js/sendSockDialog.js"],function(dialog){
                dialog && dialog.show(sn);
            })
        },

        /**
         * 添加电池
         */
        onAddBattery:function(){
            this.showBatteryEditDialog();
        },
        showBatteryEditDialog:function(id,data){
            require(["dialogbatteryEdit"],function(dialog){
                dialog && dialog.show(id,data);
            })
        },
        /**
         * 添加/修改UPS
         */
        onAddUPS:function(){
            this.showUPSEditDialog();
        },
        showUPSEditDialog:function(id,data){
            require(["dialogUPSEdit"],function(dialog){
                dialog && dialog.show(id,data);
            })
        },
        /**
         * 添加/修改BMS
         */
        onAddBMS:function(){
            this.showBMSEditDialog();
        },
        showBMSEditDialog:function(id){
            require(["dialogBMSEdit"],function(dialog){
                dialog && dialog.show(id);
            })
        },
        /**
         * 添加/修改用户单位信息
         */
        onAddCompany:function(){
            this.showCompanyEditDialog();
        },
        showCompanyEditDialog:function(id){
            require(["dialogCompanyEdit"],function(dialog){
                dialog && dialog.show(id);
            })
        },
        /**
         * 添加短信/邮箱
         */
        onAddMessage:function(){
            this.showMessageEditDialog();
        },
        showMessageEditDialog:function(id){
            require(["dialogmessageEdit"],function(dialog){
                dialog && dialog.show(id);
            })
        },
        /**
         * 添加人员
         */
        onAddPersonal:function(){
            this.showPersonalEditDialog();
        },
        showPersonalEditDialog:function(id){
            require(["dialogpersonalEdit"],function(dialog){
                dialog && dialog.show(id);
            })
        },
        /**
         * 门限修改
         */
        showLimitationEditDialog:function(id){
            require(["dialoglimitationEdit.js"],function(dialog){
                dialog.init();
                dialog.show(id);
            })
        },
        /**
         * 外控设备修改
         */
        onAddDevice:function(){
            this.showStationdeviceEditDialog();
        },
        showStationdeviceEditDialog:function(id){
            require(["dialogdeviceEdit.js"],function(dialog){
                dialog && dialog.show(id);
            })
        },
        /**
         * 其他参数修改
         */
        saveOtherOption:function(){
            require(["common"],function(common){
                var param = common.getFormValue($("#otherOptionEdit"));



                if(parseInt(param.refresh) < 8){
                    alert('刷新频率最小间隔8秒');
                    $("#otherOptionEdit [key=refresh]").val(8);
                    return;
                }
                if(parseInt(param.collection) < 8){
                    alert('采集频率最小间隔8秒');
                    $("#otherOptionEdit [key=collection]").val(8);
                    return;
                }

                param.dismap = "0";
                param.sms_on_off = "0";
                param.email_on_off = "0";
                param.light_on_off = "0";
                param.voice_on_off = "0";
                $("#map-switch").hide();
                if($("#otherOptionEdit [key=dismap]").siblings(".jqTransformChecked").length){
                    param.dismap = "1";
                    $("#map-switch").show();
                }

                if($("#otherOptionEdit [key=sms_on_off]").siblings(".jqTransformChecked").length){
                    param.sms_on_off = "1";
                    // $("#map-switch").show();
                }
                if($("#otherOptionEdit [key=light_on_off]").siblings(".jqTransformChecked").length){
                    param.light_on_off = "1";
                    // $("#map-switch").show();
                }
                if($("#otherOptionEdit [key=voice_on_off]").siblings(".jqTransformChecked").length){
                    param.voice_on_off = "1";
                    // $("#map-switch").show();
                }

                if($("#otherOptionEdit [key=email_on_off]").siblings(".jqTransformChecked").length){
                    param.email_on_off = "1";
                    // $("#map-switch").show();
                }

                API.updateParam(param);
            })
        },
        /**
         * 站参数修改
         */
        onAddStationOption:function(){
            this.showStationOptionEditDialog();
        },
        showStationOptionEditDialog:function(id){
            require(["dialogstationOptionEdit"],function(dialog){
                dialog && dialog.show(id);
            })
        },
        /**
         * 组参数修改
         */
        showGroupOptionEditDialog:function(id){
            require(["dialoggroupOptionEdit"],function(dialog){
                dialog && dialog.show(id);
            })
        },
        /**
         * 电池参数修改
         */
        showBatteryOptionEditDialog:function(id){
            require(["dialogbatteryOptionEdit"],function(dialog){
                dialog && dialog.show(id);
            })
        },
        /**
         * 其他参数修改
         */
        showOhterOptionEditDialog:function(id){
            require(["dialogotherOptionEdit"],function(dialog){
                dialog && dialog.show(id);
            })
        },
        resize:function() {
            changeStyle();
            var _this = this,
                wH=$(window).height(),
                topH=$("div.top").outerHeight(),
                bottomH=$("div.bottom").outerHeight();

            //布局--设置容器高度
            $("div.wrap").height(wH-topH-bottomH);
            var wrapH=$("div.wrap").height();
            //布局--设置右侧底部的容器高度
            $("div.down").height(wrapH-wrapH*0.65-10);
            //$("div.up").height(wrapH*0.65-10);
            var statusH=$("div.status").is(":visible")?$("div.status").outerHeight():0,
                kscjH=$("div.kscj").is(":visible")?$("div.kscj").outerHeight():0,
                cxH=$("div.cx").is(":visible")?$("div.cx").outerHeight():0,
                dlH=$("dl.set-list").is(":visible")?$("dl.set-list").outerHeight():0;
            //布局--设置左侧导航树容器高度
            $("div.nav-tree").height(wrapH-statusH-dlH-kscjH-cxH-95);
            //布局--设置右侧上部分显示数据容器高度
            //this.setH();
            return this;
        },
        leftHide:function (){
            $("div.left").hide();
            $("div.right").css("margin-left",0);
            $("b.yx-show").show();
            if($("div.down").is(":hidden")){
                $("div.title-list span.zoom").addClass("big").attr("title","窗口还原");
            }
        },
        leftShow:function(){
            $("div.left").show();
            $("div.right").css("margin-left",$("div.left").outerWidth());
            $("b.yx-show").hide();
            if($("span.zoom").hasClass("big")){
                $("span.zoom").removeClass("big").attr("title","窗口最大化");
            }
        },
        downHide:function(remove){
            $("div.down").hide();
            if(!remove || remove.currentTarget){
                $("b.bottom-show").show();
            }else{
                $("b.bottom-show").hide();
            }
            //20160110-liuchao
            if($("div.left").is(":hidden")){
                $("div.title-list span.zoom").addClass("big").attr("title","窗口还原");
            }
            this.setH();
            if(arguments[0].currentTarget){
                Backbone.Events.trigger("listdata:refresh");
            }
            return this;
        },
        downShow:function(force){
            if( $("b.bottom-show").is(":visible") || force){
                $("div.down").show();
                //$("div.up").css("height","65%");
                $("b.bottom-show").hide();
                if($("span.zoom").hasClass("big")){
                    $("span.zoom").removeClass("big").attr("title","窗口最大化");
                }
                this.setH();
            }
            if(arguments[0].currentTarget){
                Backbone.Events.trigger("listdata:refresh");
            }
        },
        upShow:function(evt){
            var $el = $(evt.currentTarget);
            if($el.attr("class")=="zoom"){
                $el.addClass("big").attr("title","窗口还原");
                this.leftHide();
                if($("#down").is(":visible")){
                    this.downHide(evt)
                }else{
                    this.downHide(evt,true)
                }
                this.resizeAutoListWidth().resizePage();
            }else{
                $el.removeClass("big").attr("title","窗口最大化");
                this.leftShow(evt);
                this.downShow(evt);
            }
        },
        setH:function(){
            var upH=$("div.up").height(),
                subListH = $("#subListTab").is(":visible")?$("#subListTab").height(): 0,
                listBtnsH = $("#listBtns").is(":visible")?$("#listBtns").height(): 0,
                listBottomsH = $(".list-bottom").is(":visible")?$(".list-bottom").height(): 0,
                listHeaderH = $(".dataTable").is(":visible")?$(".dataTable").height(): 0,
                treeHeadH = $(".tree-info-head").is(":visible")?$(".tree-info-head").height():0;
            //$("div#list").height(upH-90-subListH-listBtnsH-listBottomsH);
            //$("div#dataItem").height(upH-subListH-listBtnsH);
            $("div#yScrollBar").height(upH-130-subListH-listBtnsH-listBottomsH-listHeaderH);
            $("div.tree-info-body").height(upH-100-subListH-listBtnsH-treeHeadH-30);
        },
        resizeAutoListWidth:function(){
            $('#auto').width($("#list").width()-$("#lock").width());
            return this;
        },
        resizePage:function(){
            $('#xScrollBar').width($("#list").width()-$("#page").width());
            return this;
        },
        switchNav:function(sys,pageType,sub){
            if(sys){
                $(".nav-list .switch-btn").removeClass('active');
                $(".nav-list ."+sys).addClass('active');
            }
            if(pageType){
                $(".item-list .switch-btn").removeClass('active');
                $(".item-list ."+pageType).addClass('active');
            }
            if(sub){
                $(".sub-list-tab .switch-btn").removeClass('active');
                $(".sub-list-tab ."+sub).addClass('active');
            }

            return this;
        },
        switchChartBtns:function(pageType){
            if(pageType){
                $(".btn-wrap .btns").hide();
                $(".btn-wrap .btns."+pageType).show();
            }

            return this;
        },
        switchBtnGroups:function(sys,pageType,sub){

            var $listBtns = $("#listBtns ."+pageType+(sub?"-"+sub:"")),
                $subListTab = $("#subListTab ."+pageType);
            $(".item-list .switch-btn").hide();
            $(".item-list ."+sys).show();

            if($subListTab.length){
                $(".sub-list-tab").show();
                $(".sub-list-tab .switch-btn").hide();
                $(".sub-list-tab ."+pageType).show();
            }else{
                $(".sub-list-tab").hide();
            }

            if($listBtns.length){
                $("#listBtns").show();
                $("#listBtns li").hide();
                $listBtns.show();
            }else{
                $("#listBtns").hide();
            }

            return this;
        },
        switchListAlertBox:function(pageType){
            $(".show-list dd").show();
            return this;

            /*$(".show-list dd").hide();
            if(/^(reportCaution|batteryLife|chargeOrDischarge|reportUilogdeviationTrend|stationInfo|manager|stationTree|message|optionSetting|limitationSetting|cautionEquipmentSetting|systemInfo|chargeOrDischargevSetting|equipmentSetting|update$)/.test(pageType)) {
                $(".alert-tips,.export").show();
            }else if(/^(qureyStation|qureyGroup|qureyBattery|qureyCaution|uilog|baseinfo|limitation|runlog|option|adminConfig|equipment|IRCollect)$/.test(pageType)){
                $(".alert-tips,.export,.stationinfo-btn").show();
            }else{
                $(".show-list dd").show();
            }
            return this;*/
        },
        resizeChartBox:function(){
            // $('#chart').height($(".down").height()-38-60);
            return this;
        },
        getListWidth:function(){
            return $("#list").width();
        },
        getListHeight:function(){
            var upH=$("div.up").height(),
                listHeaderH = $(".list-header").height()+10,
                rightHeaderH = $(".right-header").height(),
                pageH = $("#page").height();

                /*subListH = $("#subListTab").is(":visible")?$("#subListTab").height(): 0,
                listBtnsH = $("#listBtns").is(":visible")?$("#listBtns").height(): 0,
                listBottomsH = $(".list-bottom").is(":visible")?$(".list-bottom").height(): 0;*/
            return 500;
        }
    })
    function changeStyle(){
        $("body").removeClass('small');
        if($(window).width()<1640){
            $("body").addClass('small');
        }
    }

    var _ui = new ui();
    _ui.startGetCaution();
    // _ui.collectAuto();
    $(window).resize(function(){
        changeStyle();
        _ui.resize();
        _ui.resizeAutoListWidth().resizePage().resizeChartBox();
    });

    return _ui;
})