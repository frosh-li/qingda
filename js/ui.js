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
            "click .switch-btn" :"switchBtn",
            "click .ggsj":"onUpadtaTime",
            "click #startCollectBtn":"startCollect",
            "click #navSearchBtn":"onSearchNav",
            "click .tzcj":"stopCollect",
            "click .title-list .switch-btn i":"showItemsLayer"
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
            });
            _this.listenTo(Backbone.Events,"otherOption:get",function(data){
                require(["common"],function(common){
                    common.setFormValue($("#otherOptionEdit"),data);
                    $("#otherOptionEdit .jqtransform").jqTransform();
                })
            });
            _this.listenTo(Backbone.Events,"param:update",function(data){
                alert("修改成功")
            });
            _this.listenTo(Backbone.Events,"monitoring:start",function(data){
                var _data = data?data.data:[];
                $(".baojing .bg").html(_data.length||0);
            });
            _this.listenTo(Backbone.Events,"monitoring:start:fail",function(data){
                var _data = data?data.data:[];
                $(".baojing .bg").html(_data.length||0);
            });
            _this.listenTo(Backbone.Events,"station:next",function(data){
                _this.showBatteryEditDialog(false,data);
            });
            _this.listenTo(Backbone.Events,"battery:next",function(data){
                _this.showUPSEditDialog(false,data);
            });
        },
        showItemsLayer:function(evt){
            var $el = $(evt.currentTarget),
                position = $el.offset(),
                type = $el.attr('for'),
                customCols = common.cookie.getCookie(type+'Cols'),
                allCols = context.getListCols(type).concat([]),
                retCols=[],
                htmls='';
            if(customCols){
                customCols = customCols.split(',');
            }
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
                        text: "确认",
                        icons: {
                            primary: "ui-icon-heart"
                        },
                        click: function() {
                            var selectCols='';
                            $("[type=checkbox]:checked",$(this)).each(function(i,el){
                                selectCols += $(el).attr('key')+',';
                            })
                            common.cookie.setCookie(type+'Cols', selectCols.replace(/,$/,''));
                            Backbone.Events.trigger('colsChange');
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
            var $el = $(evt.currentTarget),
                $els = $el.siblings('.switch-btn');
            $els.removeClass('active');
            $el.addClass('active');
        },
        showUnsolveDialog:function(id){
            require(["js/dialog-resolveCaution"],function(dialog){
                dialog && dialog.show(id);
            })
        },
        /**
         * 左侧树形导航
         */
        onSearchNav:function(){
            var keyword = $("#navSearchKeyword").val();
            API.getNavData({
                key:keyword
            });
        },
        /**
         * 更改采集时间
         */
        onUpadtaTime:function(){
            var time = parseInt($("#collectDuration").val());
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
            return $(".tzcj").is(":visible");
        },
        startCollect:function(){
            if(this.isCollecting()){return;}
            var time = parseInt($("#collectDuration").val());
            if(time){
                $("body").addClass('collecting').everyTime(time+"s",'collect',API.collect);
                $("#startCollectBtn").hide();
                $(".tzcj").css('display','block');
            }

            return this;
        },
        stopCollect:function(){
            $("body").removeClass('collecting').stopTime('collect',API.collect);
            $("#startCollectBtn").css('display','block');
            $(".tzcj").hide();

            return this;
        },
        /**
         * 定时器---获取报警信息
         */
        startGetCaution:function(){
            if(isMonitoring){return}
            isMonitoring = true;
            $("body").everyTime('5s','monitoring',function(){
                API.getMapData(false,'realtime:mapdata');
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
            require(["js/dialog-stationEdit"],function(dialog){
                dialog && dialog.show(id);
            })
        },
        /**
         * 添加电池
         */
        onAddBattery:function(){
            this.showBatteryEditDialog();
        },
        showBatteryEditDialog:function(id,data){
            require(["js/dialog-batteryEdit"],function(dialog){
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
            require(["js/dialog-UPSEdit"],function(dialog){
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
            require(["js/dialog-BMSEdit"],function(dialog){
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
            require(["js/dialog-CompanyEdit"],function(dialog){
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
            require(["js/dialog-messageEdit"],function(dialog){
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
            require(["js/dialog-personalEdit"],function(dialog){
                dialog && dialog.show(id);
            })
        },
        /**
         * 门限修改
         */
        showLimitationEditDialog:function(id){
            require(["js/dialog-limitationEdit.js"],function(dialog){
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
            require(["js/dialog-deviceEdit.js"],function(dialog){
                dialog && dialog.show(id);
            })
        },
        /**
         * 其他参数修改
         */
        saveOtherOption:function(){
            require(["common"],function(common){
                var param = common.getFormValue($("#otherOptionEdit"));

                param.dismap = "0";
                if($("#otherOptionEdit [key=dismap]").siblings(".jqTransformChecked").length){
                    param.dismap = "1";
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
            require(["js/dialog-stationOptionEdit"],function(dialog){
                dialog && dialog.show(id);
            })
        },
        /**
         * 组参数修改
         */
        showGroupOptionEditDialog:function(id){
            require(["js/dialog-groupOptionEdit"],function(dialog){
                dialog && dialog.show(id);
            })
        },
        /**
         * 电池参数修改
         */
        showBatteryOptionEditDialog:function(id){
            require(["js/dialog-batteryOptionEdit"],function(dialog){
                dialog && dialog.show(id);
            })
        },
        /**
         * 其他参数修改
         */
        showOhterOptionEditDialog:function(id){
            require(["js/dialog-otherOptionEdit"],function(dialog){
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
            // $("div.wrap").height(wH-topH-bottomH);
            var wrapH=$("div.wrap").height();
            //布局--设置右侧底部的容器高度
            // $("div.down").height(wrapH-wrapH*0.65-10);

            var statusH=$("div.status").is(":visible")?$("div.status").outerHeight():0,
                kscjH=$("div.kscj").is(":visible")?$("div.kscj").outerHeight():0,
                cxH=$("div.cx").is(":visible")?$("div.cx").outerHeight():0,
                dlH=$("dl.set-list").is(":visible")?$("dl.set-list").outerHeight():0;
            //布局--设置左侧导航树容器高度
            $("div.nav-tree").height(wrapH-statusH-dlH-kscjH-cxH-95);
            //布局--设置右侧上部分显示数据容器高度
            // this.setH();
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
            $("div.up").css("height","100%");
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

    $(window).resize(function(){
        changeStyle();
        _ui.resize();
        _ui.resizeAutoListWidth().resizePage().resizeChartBox();
    });

    return _ui;
})